<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $dates = [
        'created_at',
        'deleted_at',
        'transaction_date',
    ];
}
