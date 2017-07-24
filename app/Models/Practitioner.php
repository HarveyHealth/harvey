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
    ];

    protected $guarded = [
        'id',
        'enabled',
        'user_id',
        'practitioner_type',
        'created_at',
        'updated_at',
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
        return $this->availability()->availabilityAsCollection();
    }

    public function getTimezoneAttribute()
    {
        return $this->user->timezone;
    }

    public function getLicenseStateAttribute()
    {
        return $this->licenses->first()->state;
    }

    public function getLicenseNumberAttribute()
    {
        return "{$this->licenses->first()->title}-{$this->licenses->first()->number}";
    }

    public function setLicenseStateAttribute($value)
    {
        return $this->licenses->first()->state = $value;
    }

    public function setLicenseNumberAttribute($value)
    {
        $pieces = empty($value) ? [null, null] : array_filter(explode('-', $value));

        if (2 == count($pieces)) {
            $this->licenses->first()->title = strtoupper($pieces[0]);
            $this->licenses->first()->number = $pieces[1];
        }

        return $pieces;
    }

    public function setSpecialtyAttribute(array $value)
    {
        return $this->attributes['specialty'] = json_encode(array_values(array_filter($value)));
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

    public function licenses()
    {
        return $this->hasMany(License::class, 'user_id', 'user_id');
    }
}
