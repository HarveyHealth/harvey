<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;
use Carbon, Lang, Schema;

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

    public function markAsComplete()
    {
        $this->status_id = self::COMPLETE_STATUS_ID;

        if (Schema::hasColumn($this->getTable(), 'completed_at')) {
            $this->completed_at = Carbon::now();
        }

        return $this->save();
    }

    public function isComplete()
    {
        return self::COMPLETE_STATUS_ID == $this->status_id;
    }

    public function isPending()
    {
        return self::PENDING_STATUS_ID == $this->status_id;
    }

    public function isNotPending()
    {
        return !$this->isPending();
    }

    public function wasShipped()
    {
        return $this->status_id >= self::SHIPPED_STATUS_ID;
    }

    public function wasNotShipped()
    {
        return !$this->wasShipped();
    }

    public function scopeShipped(Builder $builder)
    {
        return $builder->where('status_id', self::SHIPPED_STATUS_ID);
    }

    public function scopePending(Builder $builder)
    {
        return $builder->where('status_id', self::PENDING_STATUS_ID);
    }

    public function scopeCanceled(Builder $builder)
    {
        return $builder->where('status_id', self::CANCELED_STATUS_ID);
    }

    public function scopeNotCanceled(Builder $builder)
    {
        return $builder->where('status_id', '!=', self::CANCELED_STATUS_ID);
    }

    public function scopeComplete(Builder $builder)
    {
        return $builder->where('status_id', self::COMPLETE_STATUS_ID);
    }

    public function scopeNoShowPatient(Builder $builder)
    {
        return $builder->where('status_id', self::NO_SHOW_PATIENT_STATUS_ID);
    }

    public function scopeNoShowDoctor(Builder $builder)
    {
        return $builder->where('status_id', self::NO_SHOW_DOCTOR_STATUS_ID);
    }

    public function scopeGeneralConflict(Builder $builder)
    {
        return $builder->where('status_id', self::GENERAL_CONFLICT_STATUS_ID);
    }

    public function scopeRecommendedOrConfirmed(Builder $builder)
    {
        return $builder->where(function (Builder $builder)
        {
            $builder->where('status_id', self::RECOMMENDED_STATUS_ID)->orWhere('status_id', self::CONFIRMED_STATUS_ID);
        });
    }

    public function __call($method, $args)
    {
        if (starts_with($method, 'markAs')) {
            $status = strtolower(substr($method, 6));
            if (false !== ($key = array_search($status, self::STATUSES))) {
                $this->status_id = $key;
                return $this->save();
            }
        }

        return parent::__call($method, $args);
    }
}
