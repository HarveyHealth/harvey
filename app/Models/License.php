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

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
