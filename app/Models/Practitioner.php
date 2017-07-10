<?php

namespace App\Models;

use App\Lib\PractitionerAvailability;
use Illuminate\Database\Eloquent\{Model, Builder};
use Carbon\Carbon;

class Practitioner extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'graduated_at',
    ];

    protected $guarded = [
        'id',
        'enabled',
        'user_id',
        'practitioner_type',
        'created_at',
        'updated_at',
        'doctor_state_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enabledUser', function (Builder $builder) {
            return $builder->whereHas('user', function (Builder $query){
                $query->where('users.enabled', true);
            });
        });
    }

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

    public function type()
    {
        return $this->hasOne(PractitionerType::class, 'id', 'practitioner_type');
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

    public function license()
    {
        return $this->hasOne(License::class, 'id', 'license_id');
    }
}
