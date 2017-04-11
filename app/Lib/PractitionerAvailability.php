<?php

namespace App\Lib;

use Carbon\Carbon;
use App\Models\Practitioner;
use App\Models\Appointment;
use App\Lib\TimeslotManager;

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
        $practitioner_timezone = $this->practitioner->user->timezone;

        $now = Carbon::now($practitioner_timezone);
        $current_week = clone $now;

        // a place to store the data
        $weeks = [];

        for ($w = 0; $w <= 1; $w++) {

            $current_week->startOfWeek();
            $current_week->addWeeks($w);

            $schedule_slots = $this->timeslotsForSchedule();
            $appointment_slots = $this->timeslotsForAppointmentForWeek($current_week);

            // remove any appointment slots from the schedule
            $availability = array_diff($schedule_slots, $appointment_slots);

            // now walk the array and remove any half or one hour block
            // as they can't take an appointment in less than 1 1/2 hours
            $slots = $availability;
            $current_run = [];

            foreach ($slots as $slot) {

                // see if this slot is more than one greater than the current run
                $last = last($current_run);

                // we have a new run
                if (count($current_run) > 0 && $last + 1 != $slot) {

                    // if the current run is fewer than 3 elements
                    // we need to remove them
                    if (count($current_run) < 3) {

                        // remove these slots from the availability
                        foreach ($current_run as $run_slot) {
                            $key = array_search($run_slot, $availability);
                            unset($availability[$key]);
                        }

                    // otherwise, we just need to remove the last slot (for the half hour buffer)
                    } else {
                        $key = array_search($last, $availability);
                        unset($availability[$key]);
                    }

                    // reset the current run
                    $current_run = [];
                }

                // otherwise, this is part of the current run
                $current_run[] = $slot;
            }

            // remove the last slot for the half hour buffer
            array_pop($availability);

            // convert all the timeslots into day/times
            $tsm = new TimeslotManager();
            $available_slots = [];

            foreach ($availability as $slot) {
                $time_data = $tsm->dayAndTimeForTimeslot($slot);

                $day = $time_data['day'];
                $time = $time_data['time'];

                $days_to_add = date('N', strtotime($day) - 1);
                if ($days_to_add >= 7)
                    $days_to_add = 0;

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
     * @return array
     */
    private function timeslotsForSchedule()
    {
        $schedules = $this->practitioner->schedule;
        $tsm = new TimeslotManager();

        $slots = [];

        // loop through each one and build an array of timeslots
        foreach ($schedules as $schedule) {
            $start = new Carbon($schedule->start_time);
            $end = new Carbon($schedule->stop_time);

            $number_of_hours = $start->diffInHours($end);
            $number_of_half_hours = ceil($number_of_hours * 2);

            for ($i = 0; $i < $number_of_half_hours; $i++) {
                $timeslot = $tsm->timeslotForDayAndTime($schedule->day_of_week, $start);
                $start->addMinutes(30);
                $slots[$timeslot] = $schedule->id;
            }
        }

        ksort($slots);
        return array_keys($slots);
    }

    private function timeslotsForAppointmentForWeek(Carbon $week)
    {
        $practitioner_timezone = $this->practitioner->user->timezone;
        $tsm = new TimeslotManager();

        $date = clone $week;
        $date->tz = $practitioner_timezone;

        $week_of_year = $date->weekOfYear;
        $year = $date->year;

        $start_date = Carbon::createFromTimestamp(strtotime("{$year}W{$week_of_year}"));
        $end_date = clone $start_date;
        $end_date->addDays(7)->subSeconds(1);

        $slots = [];

        $appointments = Appointment::forPractitioner($this->practitioner)
                            ->withinDateRange($start_date, $end_date)
                            ->get();

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
                $timeslot = $tsm->timeslotForDayAndTime(date('l', $start->timestamp), $start);
                $start->addMinutes(30);
                $slots[$timeslot] = $appointment->id;
            }
        }

        ksort($slots);
        return array_keys($slots);
    }
}
