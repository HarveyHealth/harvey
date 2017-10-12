<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
  protected $guarded = ['id', 'created_at', 'updated_at'];
  protected $dates = ['created_at', 'updated_at'];
}
