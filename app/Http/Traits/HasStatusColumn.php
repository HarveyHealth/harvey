<?php

namespace App\Http\Traits;

use Carbon;
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

    public function isCanceled()
    {
        return $this->status_id == self::CANCELED_STATUS_ID;
    }

    public function isNotCanceled()
    {
        return !$this->isCanceled();
    }

    public function markAsCanceled()
    {
        $this->status_id = self::CANCELED_STATUS_ID;

        return $this->save();
    }

    public function isComplete()
    {
        return self::COMPLETE_STATUS_ID == $this->status_id;
    }

    public function isNotComplete()
    {
        return !$this->isComplete();
    }

    public function markAsComplete()
    {
        $this->status_id = self::COMPLETE_STATUS_ID;
        $this->completed_at = Carbon::now();

        return $this->save();
    }

    public function isPending()
    {
        return self::PENDING_STATUS_ID == $this->status_id;
    }

    public function isNotPending()
    {
        return !$this->isPending();
    }
}
