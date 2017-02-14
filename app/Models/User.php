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
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'enabled', 'user_type', 'password', 'api_token',
                            'remember_token', 'terms_accepted_at', 'phone_verified_at',
                            'email_verified_at','created_at', 'updated_at'];

    protected $dates = ['created_at','updated_at','terms_accepted_at','phone_verified_at','email_verified_at'];

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

    public function superUser()
    {
        return $this->user_type == 'admin';
    }
    
    public function isPatient()
    {
        return $this->user_type == 'patient';
    }
    
    public function isPractitioner()
    {
        return $this->user_type == 'practitioner';
    }
    
    public function consultsWithUser(User $user)
    {
        return Appointment::wherePatientUserId($user->id)->wherePractitionerUserId($this->id)->count() > 0;
    }

    
    /* Mailable Interface Methods */
    public function emailVerificationToken()
    {
        return sha1($this->id . '|' . $this->email . '|' . $this->created_at);
    }

    public function emailVerificationURL()
    {
        return secure_url("/verify/{$this->id}/" . $this->emailVerificationToken());
    }
}
