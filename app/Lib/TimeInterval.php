<?php

namespace App\Lib;

/**
 * Manages Time Intervals
 */
class TimeInterval
{
    private $_seconds;

    const SECONDS_PER_MINUTE = 60;
    const MINUTES_PER_HOUR = 60;
    const MINUTES_PER_MONTH = 43800;
    const HOURS_PER_DAY = 24;
    const HOURS_PER_WEEK = 168;
    const DAYS_PER_WEEK = 7;
    const DAYS_PER_MONTH = 29.530588853;
    const DAYS_PER_YEAR = 365.256363051;

    /**
     * Sets the number of seconds for the time interval
     * @param  float $seconds the number of seconds
     * @return TimeInterval  this object instance
     */
    public static function seconds($seconds)
    {
        $ti = new TimeInterval;
        $ti->_seconds = 0;
        return $ti->addSeconds($seconds);
    }

    /**
     * Sets the number of minutes for the time interval
     * @param  float $minutes the number of minutes
     * @return TimeInterval  this object instance
     */
    public static function minutes($minutes)
    {
        $ti = new TimeInterval;
        $ti->seconds(0);
        return $ti->addMinutes($minutes);
    }

    /**
     * Sets the number of hours for the time interval
     * @param  float $hours the number of hours
     * @return TimeInterval  this object instance
     */
    public static function hours($hours)
    {
        $ti = new TimeInterval;
        $ti->seconds(0);
        return $ti->addHours($hours);
    }

    /**
     * Sets the number of days for the time interval
     * @param  float $days the number of days
     * @return TimeInterval  this object instance
     */
    public static function days($days)
    {
        $ti = new TimeInterval;
        $ti->seconds(0);
        return $ti->addDays($days);
    }

    public static function day()
    {
        return static::days(1);
    }

    /**
     * Sets the number of weeks for the time interval
     * @param  float $weeks the number of weeks
     * @return TimeInterval  this object instance
     */
    public static function weeks($weeks)
    {
        $ti = new TimeInterval;
        $ti->seconds(0);
        return $ti->addWeeks($weeks);
    }

    /**
     * Sets the number of months for the time interval
     * @param  float $months the number of months
     * @return TimeInterval  this object instance
     */
    public static function months($months)
    {
        $ti = new TimeInterval;
        $ti->seconds(0);
        return $ti->addMonths($months);
    }

    /**
     * Sets the number of years for the time interval
     * @param  float $years the number of years
     * @return TimeInterval  this object instance
     */
    public static function years($years)
    {
        $ti = new TimeInterval;
        $ti->seconds(0);
        return $ti->addYears($years);
    }



    /**
     * Adds a given number of seconds to the current time interval
     * @param float $seconds The number of seconds to add
     * @return TimeInterval  this object instance
     */
    public function addSeconds($seconds)
    {
        $this->_seconds = $this->_seconds + $seconds;
        return $this;
    }

    /**
     * Adds a given number of minutes to the current time interval
     * @param float $minutes The number of minutes to add
     * @return TimeInterval  this object instance
     */
    public function addMinutes($minutes)
    {
        return $this->addSeconds($minutes * self::SECONDS_PER_MINUTE);
    }

    /**
     * Adds a given number of hours to the current time interval
     * @param float $hours The number of hours to add
     * @return TimeInterval  this object instance
     */
    public function addHours($hours)
    {
        return $this->addMinutes($hours * self::MINUTES_PER_HOUR);
    }

    /**
     * Adds a given number of days to the current time interval
     * @param float $days The number of days to add
     * @return TimeInterval  this object instance
     */
    public function addDays($days)
    {
        return $this->addHours($days * self::HOURS_PER_DAY);
    }

    /**
     * Adds a given number of weeks to the current time interval
     * @param float $weeks The number of weeks to add
     * @return TimeInterval  this object instance
     */
    public function addWeeks($weeks)
    {
        return $this->addDays($weeks * self::DAYS_PER_WEEK);
    }

    /**
     * Adds a given number of months to the current time interval
     * @param float $months The number of months to add
     * @return TimeInterval  this object instance
     */
    public function addMonths($months)
    {
        return $this->addDays($months * self::DAYS_PER_MONTH);
    }

    /**
     * Adds a given number of years to the current time interval
     * @param float $years The number of years to add
     * @return TimeInterval  this object instance
     */
    public function addYears($years)
    {
        return $this->addDays($years * self::DAYS_PER_YEAR);
    }



    /**
     * Subtracts a given number of seconds to the current time interval
     * @param float $seconds The number of seconds to subtract
     * @return TimeInterval  this object instance
     */
    public function subtractSeconds($seconds)
    {
        $this->addSeconds(-$seconds);
        return $this;
    }

    /**
     * Subtracts a given number of minutes to the current time interval
     * @param float $minutes The number of minutes to subtract
     * @return TimeInterval  this object instance
     */
    public function subtractMinutes($minutes)
    {
        return $this->subtractSeconds($minutes * self::SECONDS_PER_MINUTE);
    }

    /**
     * Subtracts a given number of hours to the current time interval
     * @param float $hours The number of hours to subtract
     * @return TimeInterval  this object instance
     */
    public function subtractHours($hours)
    {
        return $this->subtractMinutes($hours * self::MINUTES_PER_HOUR);
    }

    /**
     * Subtracts a given number of days to the current time interval
     * @param float $days The number of days to subtract
     * @return TimeInterval  this object instance
     */
    public function subtractDays($days)
    {
        return $this->subtractHours($days * self::HOURS_PER_DAY);
    }

    /**
     * Subtracts a given number of weeks to the current time interval
     * @param float $weeks The number of weeks to subtract
     * @return TimeInterval  this object instance
     */
    public function subtractWeeks($weeks)
    {
        return $this->subtractDays($weeks * self::DAYS_PER_WEEK);
    }

    /**
     * Subtracts a given number of months to the current time interval
     * @param float $months The number of months to subtract
     * @return TimeInterval  this object instance
     */
    public function subtractMonths($months)
    {
        return $this->subtractDays($months * self::DAYS_PER_MONTH);
    }

    /**
     * Subtracts a given number of years to the current time interval
     * @param float $years The number of years to subtract
     * @return TimeInterval  this object instance
     */
    public function subtractYears($years)
    {
        return $this->subtractDays($years * self::DAYS_PER_YEAR);
    }



    /**
     * Returns the number of seconds in the time interval
     * @return float The number of seconds
     */
    public function toSeconds()
    {
        return $this->_seconds;
    }

    /**
     * Returns the number of minutes in the time interval
     * @return float The number of minutes
     */
    public function toMinutes()
    {
        return $this->toSeconds() / self::SECONDS_PER_MINUTE;
    }

    /**
     * Returns the number of hours in the time interval
     * @return float The number of hours
     */
    public function toHours()
    {
        return $this->toMinutes() / self::MINUTES_PER_HOUR;
    }

    /**
     * Returns the number of days in the time interval
     * @return float The number of days
     */
    public function toDays()
    {
        return $this->toHours() / self::HOURS_PER_DAY;
    }

    /**
     * Returns the number of weeks in the time interval
     * @return float The number of weeks
     */
    public function toWeeks()
    {
        return $this->toDays() / self::DAYS_PER_WEEK;
    }

    /**
     * Returns the number of months in the time interval
     * @return float The number of months
     */
    public function toMonths()
    {
        return $this->toDays() / self::DAYS_PER_MONTH;
    }

    /**
     * Returns the number of years in the time interval
     * @return float The number of years
     */
    public function toYears()
    {
        return $this->toDays() / self::DAYS_PER_YEAR;
    }
}
