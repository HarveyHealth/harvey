<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Builder};
use Carbon;

class Prescription extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'created_by_user_id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

}
