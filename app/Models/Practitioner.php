<?php

namespace App\Models;

use App\Lib\{PractitionerAvailability, TimeInterval};
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

    public function setLicensesAttribute(array $licenses)
    {
        $this->licenses()->delete();

        foreach ($licenses as $license) {
            if (empty($license['number']) || empty($license['title']) || empty($license['state'])) {
                continue;
            }
            $this->licenses()->create([
                'number' => $license['number'],
                'state' => $license['state'],
                'title' => $license['title'],
            ]);
        }

        return $licenses;
    }

    public function setSpecialtyAttribute(array $value)
    {
        return $this->attributes['specialty'] = json_encode(array_values(array_filter($value)));
    }

    public function availability()
    {
        $buffer_in_hours = currentUserIsAdminOrPractitioner() ? 0 : PractitionerAvailability::DEFAULT_AVAILABILITY_BUFFER_IN_HOURS;

        return new PractitionerAvailability($this, $buffer_in_hours);
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

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'practitioner_id', 'id');
    }

    public function schedule()
    {
        return $this->hasMany(PractitionerSchedule::class, 'practitioner_id', 'id');
    }

    public function licenses()
    {
        return $this->hasMany(License::class, 'user_id', 'user_id');
    }
}
