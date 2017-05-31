<?php

namespace App\Lib;

use App\Lib\TimeslotManager;
use App\Models\Practitioner;
use Carbon\Carbon;

class PractitionerAvailability
{
    protected $practitioner;

    public function __construct(Practitioner $practitioner)
    {
        $this->practitioner = $practitioner;
    }

    /**
     * @return array
     */
    public function availability()
    {
        $practitioner_timezone = $this->practitioner->timezone;

        $now = Carbon::now($practitioner_timezone);
        $current_week = clone $now;

        // a place to store the data
        $weeks = [];

        for ($w = 0; $w <= 1; $w++) {
            $current_week->startOfWeek();
            $current_week->addWeeks($w);

            $availability = $this->validAvailabilitySlotsForWeek($current_week);

            // convert all the timeslots into day/times
            $available_slots = [];

            foreach ($availability as $slot) {
                $time_data = TimeslotManager::dayAndTimeForTimeslot($slot);

                $day = $time_data['day'];
                $time = $time_data['time'];

                $days_to_add = date('N', strtotime($day) - 1);
                if ($days_to_add >= 7) {
                    $days_to_add = 0;
                }

                $date = clone $current_week;
                $date->startOfWeek();
                $date->addDays($days_to_add);
                $date->hour = date('H', strtotime($time));
                $date->minute = date('i', strtotime($time));

                // if the date is defined outside the current week
                // ignore it
                if ($now->gte($date)) {
                    continue;
                }

                // convert to UTC
                $date->tz = 'UTC';

                $available_slots[] = $date->format('l H:i');
            }

            $weeks['week ' . ($w + 1)] = $available_slots;
        }

        return $weeks;
    }

    /**
     * @param Carbon $date
     * @return array
     */
    public function openAvailabilitySlotsForWeekBeginning(Carbon $date)
    {
        $schedule_slots = $this->timeslotsForSchedule();
        $appointment_slots = $this->timeslotsForAppointmentForWeek($date);

        return array_diff($schedule_slots, $appointment_slots);
    }

    /**
     * @param Carbon $date
     * @return array
     */
    public function validAvailabilitySlotsForWeek(Carbon $date)
    {
        $timeslots = $this->openAvailabilitySlotsForWeekBeginning($date);
        sort($timeslots);

        $consecutive_slots = [];
        $availability_slots = [];
        foreach ($timeslots as $index => $slot) {
            if (count($timeslots) < 3) {
                continue;
            }

            if (array_last($timeslots) == $slot) {
                if ($slot - 1 == $timeslots[$index - 1]) {
                    $consecutive_slots[] = $slot;
                    $availability_slots[] = $consecutive_slots;
                }
            } elseif ($slot + 1 == $timeslots[$index + 1]) {
                $consecutive_slots[] = $slot;
                $consecutive_slots[] = $timeslots[$index + 1];
            } else {
                $availability_slots[] = $consecutive_slots;
                $consecutive_slots = [];
            }
        }

        $coll = collect($availability_slots);

        // Remove duplicate slot ids
        $coll->transform(function ($group) {
            return array_unique($group);
        });

        // Remove any groups with less than 3 slots
        $valid_slots = $coll->filter(function ($group) {
            return count($group) >= 3;
        });

        // Remove the last 30 min slot from the group
        $valid_slots->transform(function ($group) {
            return array_slice($group, 0, -1);
        });

        // Flatten the array and return
        return array_flatten($valid_slots->toArray());
    }

    /**
     * @param Carbon $date
     * @return boolean
     */
    public function canScheduleAt(Carbon $appointmentAt) {
        $appointmentAt->tz = $this->practitioner->timezone;
        $appointmentAtTimeslot = TimeslotManager::timeslotForDayAndTime($appointmentAt->format('l'), $appointmentAt->format('H:i'));

        return in_array($appointmentAtTimeslot, $this->practitioner->availability()->validAvailabilitySlotsForWeek($appointmentAt));
    }

    /**
     * @return array
     */
    private function timeslotsForSchedule()
    {
        $schedules = $this->practitioner->schedule;

        $slots = [];

        // loop through each one and build an array of timeslots
        foreach ($schedules as $schedule) {
            $start = new Carbon($schedule->start_time);
            $end = new Carbon($schedule->stop_time);

            $number_of_half_hours = ceil($start->diffInSeconds($end) / 1800);

            for ($i = 0; $i < $number_of_half_hours; $i++) {
                $timeslot = TimeslotManager::timeslotForDayAndTime($schedule->day_of_week, $start);
                $start->addMinutes(30);
                $slots[$timeslot] = $schedule->id;
            }
        }

        ksort($slots);
        return array_keys($slots);
    }

    private function timeslotsForAppointmentForWeek(Carbon $week)
    {
        $practitioner_timezone = $this->practitioner->timezone;

        $date = clone $week;
        $date->tz = $practitioner_timezone;

        $week_of_year = $date->weekOfYear;
        $year = $date->year;

        $start_date = Carbon::createFromTimestamp(strtotime("{$year}W{$week_of_year}"));
        $end_date = clone $start_date;
        $end_date->addDays(7)->subSeconds(1);

        $slots = [];

        $appointments = $this->practitioner->appointments()->pending()->withinDateRange($start_date, $end_date)->byAppointmentAtAsc()->get();

        // loop through each one and build an array of timeslots
        foreach ($appointments as $appointment) {
            $start = new Carbon($appointment->appointment_at, 'UTC');

            // convert appointment to practitioner timezone
            $start->tz = $practitioner_timezone;

            // all appointments have a 90 minute block
            // 60 for the appointment and
            // 30 for patient notes, coffee break, etc.
            $number_of_half_hours = 3;

            for ($i = 0; $i < $number_of_half_hours; $i++) {
                $timeslot = TimeslotManager::timeslotForDayAndTime($start->format('l'), $start);
                $start->addMinutes(30);
                $slots[$timeslot] = $appointment->id;
            }
        }

        ksort($slots);
        return array_keys($slots);
    }


}
