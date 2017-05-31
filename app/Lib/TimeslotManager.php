<?php

namespace App\Lib;

use Cache;
use Storage;

class TimeslotManager
{

    public static function getTimeslotsArray()
    {
        return Cache::remember('compiled timeslots', TimeInterval::years(1)->toMinutes(), function () {
            $data = Storage::disk('resources')->get('assets/other/timeslots.txt');
            return unserialize($data);
        });
    }

    public static function getMinutesFromTimeString($timestring)
    {
        return date('i', strtotime($timestring));
    }

    public static function getNearestThirtyMinIntervalTimeFor($timestring)
    {
        return static::getMinutesFromTimeString($timestring) >= 30 ? "30" : "00";
    }

    /**
     * Returns the slot id for the specified day and time
     * @param $day Sunday
     * @param $time "11:00"
     * @return integer slot id
     */
    public static function timeslotForDayAndTime($day, $time)
    {
        $minute = static::getNearestThirtyMinIntervalTimeFor($time);
        $time = date("H:{$minute}:00", strtotime($time));

        // cache for better performance
        // no need to walk the array every time
        $time_interval = TimeInterval::years(1)->toMinutes();

        $key = 'timeslot-for-day-time: ' . $day . ':' . $time;

        $slot = Cache::remember($key, $time_interval, function () use ($day, $time) {

            // return the first match to the day and time
            $slot = array_first(static::getTimeslotsArray(), function ($slot) use ($day, $time) {
                return ($day == $slot[1] && $time == $slot[2]);
            });

            return $slot[0];
        });

        return $slot;
    }

    public static function dayAndTimeForTimeslot($timeslot)
    {
        // cache for better performance
        // no need to walk the array every time
        $time_interval = TimeInterval::years(1)->toMinutes();

        $key = 'timeslot-id: ' . $timeslot;

        $slot = Cache::remember($key, $time_interval, function () use ($timeslot) {

            // return the first match to the timeslot id
            $slot = array_first(static::getTimeslotsArray(), function ($slot) use ($timeslot) {
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
