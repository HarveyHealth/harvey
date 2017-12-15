<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasVisibilityColumn
{
    public function getVisibilityAttribute()
    {
        return empty(self::VISIBILITIES[$this->visibility_id]) ? null : self::VISIBILITIES[$this->visibility_id];
    }

    public function setVisibilityAttribute($value)
    {
        if (false !== ($key = array_search($value, self::VISIBILITIES))) {
            $this->visibility_id = $key;
        }

        return $value;
    }

    public function scopePublic(Builder $builder)
    {
        return $builder->where('visibility_id', '<=', self::PUBLIC_VISIBILITY_ID);
    }

    public function scopePatients(Builder $builder)
    {
        return $builder->where('visibility_id', '<=', self::PATIENTS_VISIBILITY_ID);
    }

    public function scopePractitioners(Builder $builder)
    {
        return $builder->where('visibility_id', '<=', self::PRACTITIONERS_VISIBILITY_ID);
    }

    public function scopeAdmins(Builder $builder)
    {
        return $builder->where('visibility_id', '<=', self::ADMINS_VISIBILITY_ID);
    }
}
