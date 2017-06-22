<?php

namespace App\Models;

use App\Lib\PractitionerAvailability;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Practitioner extends Model
{

    const UNKNOWN_DOCTOR_STATE_ID = 0;

    const DOCTOR_STATES = [
        self::UNKNOWN_DOCTOR_STATE_ID => 'unknown',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
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

        static::addGlobalScope('enabled', function (Builder $builder) {
            $builder->whereHas('user', function (Builder $builder) {
                $builder->where('users.enabled', true);
            });
        });
    }

    public function getAvailabilityAttribute()
    {
        return $this->availability()->availabilityAsCollection();
    }

    public function getTimezoneAttribute()
    {
        return $this->user->timezone;
    }

    public function getDoctorStateAttribute()
    {
        return empty(self::DOCTOR_STATES[$this->doctor_state_id]) ? null : self::DOCTOR_STATES[$this->doctor_state_id];
    }

    public function setDoctorStateAttribute($value)
    {
        if (false !== ($key = array_search($value, self::DOCTOR_STATES))) {
            $this->doctor_state_id = $key;
        }

        return $value;
    }

    public function getDoctorStateFriendlyName()
    {
        return $this->doctor_state ? Lang::get("practitioners.doctor_state.{$this->doctor_state}") : null;
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
}
