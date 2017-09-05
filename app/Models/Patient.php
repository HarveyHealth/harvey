<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Builder};

class Patient extends Model
{
    protected $guarded = [
        'created_at',
        'enabled',
        'id',
        'stripe_brand',
        'stripe_customer_id',
        'stripe_expiry_month',
        'stripe_expiry_year',
        'stripe_last_four',
        'intake_token',
        'updated_at',
        'user_id',
    ];

    protected $dates = ['created_at','updated_at'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enabledUser', function (Builder $builder) {
            return $builder->whereHas('user', function (Builder $query){
                $query->where('users.enabled', true);
            });
        });
    }

    public function getIntakeValidationTokenAttribute()
    {
        return sha1("{$this->id}|{$this->created_at}");
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function notes()
    {
        return $this->hasMany(PatientNote::class, 'patient_id', 'id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'patient_id', 'id');
    }

    public function soapNotes()
    {
        return $this->hasMany(SoapNote::class, 'patient_id', 'id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id', 'id');
    }
}
