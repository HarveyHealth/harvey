<?php

namespace App\Lib;

use App\Lib\TimeInterval;
use App\Lib\CSV;
use Carbon\Carbon;

class TimeslotManager
{
    private $timeslots;

    public function __construct()
    {
        $time_interval = TimeInterval::years(1);

        $timeslots = \Cache::remember('compiled timeslots', $time_interval->toMinutes(), function () {
            $filepath = resource_path('assets/other/timeslots.txt');
            $data = file_get_contents($filepath);

            $array = unserialize($data);

            return $array;
        });

        $this->timeslots = $timeslots;
    }

    public function timeslotForDayAndTime($day, $time)
    {
        // get the time on either the 00 oe 30 mark
        $minute = date('i', strtotime($time));

        if ($minute >= 30) {
            $time = date('H:30:00', strtotime($time));
        } else {
            $time = date('H:00:00', strtotime($time));
        }

        // cache for better performance
        // no need to walk the array every time
        $time_interval = TimeInterval::years(1);

        $key = 'timeslot-for-day-time: ' . $day . ':' . $time;

        $slot = \Cache::remember($key, $time_interval->toMinutes(), function () use ($day, $time) {

            // return the first match to the day and time
            $slot = array_first($this->timeslots, function ($slot) use ($day, $time) {
                return ($day == $slot[1] && $time == $slot[2]);
            });

            return $slot[0];
        });

        return $slot;
    }

    public function dayAndTimeForTimeslot($timeslot)
    {
        // cache for better performance
        // no need to walk the array every time
        $time_interval = TimeInterval::years(1);

        $key = 'timeslot-id: ' . $timeslot;

        $slot = \Cache::remember($key, $time_interval->toMinutes(), function () use ($timeslot) {

            // return the first match to the timeslot id
            $slot = array_first($this->timeslots, function ($slot) use ($timeslot) {
                return ($timeslot == $slot[0]);
            });

            $this_slot = [
                'slot_id', $slot[0],
                'day' => $slot[1],
                'time' => $slot[2],
            ];

            return $this_slot;
        });

        return $slot;
    }
}
