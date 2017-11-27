<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Builder, Model};
use App\Lib\TimeInterval;
use Cache;

class Condition extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    const ALL_CACHE_KEY = 'all_conditions';

    public function scopeEnabled(Builder $builder)
    {
        return $builder->where('enabled', true);
    }

    public static function getAllFromCache()
    {
        return Cache::remember(self::ALL_CACHE_KEY, TimeInterval::weeks(2)->toMinutes(), function () {
            return Condition::enabled()->get();
        });
    }
}
