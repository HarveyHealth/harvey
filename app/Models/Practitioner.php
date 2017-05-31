<?php

namespace App\Models;

use App\Lib\PractitionerAvailability;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Practitioner extends Model
{
    protected $dates = ['created_at','updated_at'];

    public function getAvailabilityAttribute()
    {
        return $this->availability()->availability();
    }

    public function getTimezoneAttribute()
    {
        return $this->user->timezone;
    }

    public function availability()
    {
        return new PractitionerAvailability($this);
    }

    /*
     * Relationships
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function notes()
    {
        return $this->hasMany(PatientNote::class, 'practitioner_id', 'id');
    }

    public function chartNotes()
    {
        return $this->hasMany(ChartNote::class, 'practitioner_id', 'id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'practitioner_id', 'id');
    }

    public function schedule()
    {
        return $this->hasMany(PractitionerSchedule::class, 'practitioner_id', 'id');
    }

    public function test()
    {
        return $this->hasMany(Test::class, 'practitioner_id', 'id');
    }
}
