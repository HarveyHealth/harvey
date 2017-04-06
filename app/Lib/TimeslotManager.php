<?php

namespace App\Lib;

use \Cache;
use \Storage;

class TimeslotManager
{
    private $timeslots;

    public function __construct()
    {
        // Store timeslots cache for one year
        $time_interval = TimeInterval::years(1)->toMinutes();
    
        $this->timeslots = Cache::remember('compiled timeslots', $time_interval, function () {
            return $this->getTimeslotsArray();
        });
    }
    
    public function getTimeslotsArray()
    {
        $data = Storage::disk('resources')->get('assets/other/timeslots.txt');
        return unserialize($data);
    }
    
    public function getMinutesFromTimeString($timestring)
    {
        return date('i', strtotime($timestring));
    }
    
    public function getNearestThirtyMinIntervalTimeFor($timestring)
    {
        return $this->getMinutesFromTimeString($timestring) >= 30 ? "30" : "00";
    }
    
    /**
     * Returns the slot id for the specified day and time
     * @param $day Sunday
     * @param $time "11:00"
     * @return integer slot id
     */
    public function timeslotForDayAndTime($day, $time)
    {
        $minute = $this->getNearestThirtyMinIntervalTimeFor($time);
        $time = date("H:{$minute}:00", strtotime($time));

        // cache for better performance
        // no need to walk the array every time
        $time_interval = TimeInterval::years(1)->toMinutes();

        $key = 'timeslot-for-day-time: ' . $day . ':' . $time;

        $slot = Cache::remember($key, $time_interval, function () use ($day, $time) {

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
        $time_interval = TimeInterval::years(1)->toMinutes();

        $key = 'timeslot-id: ' . $timeslot;

        $slot = Cache::remember($key, $time_interval, function () use ($timeslot) {

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
