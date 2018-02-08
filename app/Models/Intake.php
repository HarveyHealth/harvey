<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Intake extends Model
{
    use SoftDeletes;

    protected $dates = [
        'completed_at',
        'created_at',
        'deleted_at',
    ];

    protected $guarded = [
        'id',
        'user_id',
    ];

    public function getRouteKeyName()
    {
        return 'token';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDataAttribute()
    {
        return json_decode($this->getAttributeFromArray('data'), true);
    }

    public function setDataAttribute(array $value)
    {
        return $this->attributes['data'] = json_encode($value);
    }
}
