<?php

namespace App\Http\Traits;

use Lang;

trait HasStatusColumn
{
    public function getStatusAttribute()
    {
        return empty(self::STATUSES[$this->status_id]) ? null : self::STATUSES[$this->status_id];
    }

    public function setStatusAttribute($value)
    {
        if (false !== ($key = array_search($value, self::STATUSES))) {
            $this->status_id = $key;
        }

        return $value;
    }

    public function getStatusFriendlyName()
    {
        $tableName = $this->getTable();

        return $this->status ? Lang::get("{$tableName}.status.{$this->status}") : null;
    }
}
