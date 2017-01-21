<?php

namespace App\Models;

use App\Models\PatientNote;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Interfaces\Mailable;

class User extends Authenticatable implements Mailable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'enabled', 'user_type', 'api_token',
                            'stripe_customer_id', 'terms_accepted_at',
                            'phone_verified_at', 'email_verified_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enabled', function (Builder $builder) {
            $builder->where('enabled', true);
        });
    }

    public function patientNotes()
    {
        return $this->hasMany(PatientNote::class, 'patient_user_id', 'id');
    }

    public function practitionerNotes()
    {
        return $this->hasMany(PatientNote::class, 'practitioner_user_id', 'id');
    }

    /*
     * Returns the concatenated full name
     */
    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /*
     * Returns the image URL for this user
     * If they don't have one, it returns a default
     */
    public function imageURL()
    {
        if (!empty($this->image_url)) {
            return $this->image_url;
        }

        return config('app.default_image_url');
    }



    /* Mailable Interface Methods */
    public function emailVerificationToken()
    {
        return sha1($this->id . '|' . $this->email . '|' . $this->created_at);
    }

    public function emailVerificationURL()
    {
        return url("/verify/{$this->id}/" . $this->emailVerificationToken());
    }
}
