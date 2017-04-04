<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $dates = ['created_at','updated_at'];
    
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
