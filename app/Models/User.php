<?php

namespace App\Models;

use App\Http\Interfaces\Mailable;
use App\Mail\VerifyEmailAddress;
use App\Models\Message;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;
use Carbon;
use Log;
use Mail;

class User extends Authenticatable implements Mailable
{
    use HasApiTokens, Notifiable, Searchable;

    public $asYouType = true;

    protected $guarded = ['id', 'enabled', 'password', 'remember_token',
                            'terms_accepted_at', 'phone_verified_at',
                            'email_verified_at', 'created_at', 'updated_at'];

    protected $dates = ['created_at','updated_at','terms_accepted_at',
                        'phone_verified_at','email_verified_at'];

    protected $hidden = ['password', 'remember_token'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enabled', function (Builder $builder) {
            $builder->where('users.enabled', true);
        });
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->fullName(),
        ];
    }

    public function getImageUrlAttribute()
    {
        return $this->getAttributeFromArray('image_url') ?: config('app.default_image_url');
    }

    public function getTypeAttribute()
    {
        return $this->userType();
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function practitioner()
    {
        return $this->hasOne(Practitioner::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function appointments()
    {
        if ($this->isPatient()) {
            return $this->hasManyThrough(Appointment::class, Patient::class);
        } else {
            return $this->hasManyThrough(Appointment::class, Practitioner::class);
        }
    }

    public function nextUpcomingAppointment()
    {
        return $this->appointments()->upcoming()->first();
    }

    public function hasUpcomingAppointment()
    {
        return count($this->nextUpcomingAppointment()) == 1;
    }

    public function tests()
    {
        if ($this->isPatient()) {
            return $this->hasManyThrough(Test::class, Patient::class);
        } else {
            return $this->hasManyThrough(Test::class, Practitioner::class);
        }
    }

    public function userType()
    {
        if ($this->isPatient()) {
            return 'patient';
        } elseif ($this->isPractitioner()) {
            return 'practitioner';
        } elseif ($this->isAdmin()) {
            return 'admin';
        } else {
            throw new \Exception("Unable to determine user's type.");
        }
    }

    public function isPatient()
    {
        return $this->patient != null;
    }

    public function isPractitioner()
    {
        return $this->practitioner != null;
    }

    public function isAdmin()
    {
        return $this->admin != null;
    }

    public function isAdminOrPractitioner()
    {
        return $this->isAdmin() || $this->isPractitioner();
    }

    public function fullName()
    {
        $fullName = trim($this->first_name . ' ' . $this->last_name);

        return  empty($fullName) ? null : $fullName;
    }

    public function passwordSet()
    {
        return isset($this->password);
    }

    public function passwordNotSet()
    {
        return !$this->passwordSet();
    }

    public function emailVerificationTokenMismatch($token)
    {
        return $token != $this->emailVerificationToken();
    }

    public function sendVerificationEmail()
    {
        Mail::to($this)->send(new VerifyEmailAddress($this));
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

    public function isAllowedToSendMessages()
    {
        $recentMessagesCount = Message::from($this)->newestThan(Carbon::parse('-10 minutes'))->count();

        if ($recentMessagesCount <= 5) {
            return true;
        }

        Log::warning("User ID #{$this->id} blocked to send new message. User created too many messages in the last 10 minutes.");
        return false;
    }

    public function scopeMatching($query, $term)
    {
        return $query->where('first_name', 'LIKE', "%{$term}%")
        ->orWhere('last_name', 'LIKE', "%{$term}%")
        ->orWhere('email', 'LIKE', "%{$term}%");
    }

    public function scopePractitioners($query)
    {
        return $query->join('practitioners', 'practitioners.user_id', 'users.id')->select('users.*');
    }

    public function scopePatients($query)
    {
        return $query->join('patients', 'patients.user_id', 'users.id')->select('users.*');
    }

    public function scopeAdmins($query)
    {
        return $query->join('admins', 'admins.user_id', 'users.id')->select('users.*');
    }
}
