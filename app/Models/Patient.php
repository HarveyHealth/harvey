<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $dates = ['created_at','updated_at'];
    
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    
    public function notes()
    {
        return $this->hasMany(PatientNote::class, 'patient_id', 'id');
    }
    
    public function chartNotes()
    {
        return $this->hasMany(ChartNote::class, 'patient_id', 'id');
    }
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'id');
    }
    
    public function test()
    {
        return $this->hasMany(Test::class, 'patient_id', 'id');
    }
}
