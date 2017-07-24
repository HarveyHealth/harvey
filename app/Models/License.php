<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'number',
        'state',
        'title',
    ];

    public function getNumberAttribute()
    {
        return "{$this->title}-{$this->getAttributes()['number']}";
    }

    public function setNumberAttribute($value)
    {
        $pieces = array_filter(explode('-', $value));

        if (2 == count($pieces)) {
            list($this->title, $this->attributes['number']) = $pieces;
        }

        return $value;
    }
}
