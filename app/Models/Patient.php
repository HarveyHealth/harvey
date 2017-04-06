<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $guarded = ['id', 'enabled', 'user_id', 'stripe_customer_id',
                            'stripe_expiry_month', 'stripe_expiry_year',
                            'stripe_brand', 'stripe_last_four',
                            'created_at', 'updated_at'];
    
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
