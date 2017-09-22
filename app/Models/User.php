<?php

namespace App\Models;

use App\Http\Interfaces\Mailable;
use App\Http\Traits\{IsNot, Textable};
use App\Lib\{PhoneNumberVerifier, TimeInterval, TransactionalEmail, ZipCodeValidator};
use App\Mail\VerifyEmailAddress;
use App\Models\Message;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;
use Stripe\Customer;
use Cache, Carbon, Exception, Log, Mail;

class User extends Authenticatable implements Mailable
{
    use HasApiTokens, Notifiable, Searchable, IsNot, Textable;

    public $asYouType = true;

    public $allowedSortBy = [
        'id',
        'created_at',
        'email',
        'first_name',
        'last_name',
        'terms_accepted_at',
    ];

    protected $fillable = [
      'provider',
      'provider_id',
    ];

    protected $guarded = [
        'id',
        'card_brand',
        'card_last_four',
        'created_at',
        'email_verified_at',
        'enabled',
        'intake_completed_at',
        'password',
        'phone_verified_at',
        'remember_token',
        'stripe_id',
        'terms_accepted_at',
        'trial_ends_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'email_verified_at',
        'intake_completed_at',
        'phone_verified_at',
        'updated_at',
    ];

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
            'full_name' => $this->full_name,
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

    public function getStateAttribute()
    {
        if (!empty($this->attributes['state'])) {
            return $this->attributes['state'];
        } elseif (empty($this->zip)) {
            return null;
        }

        return app()->make(ZipCodeValidator::class)->setZip($this->zip)->getState();
    }

    public function getFullNameAttribute()
    {
        $fullName = trim("{$this->first_name} {$this->last_name}");

        return  empty($fullName) ? null : $fullName;
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
            throw new Exception("Unable to determine type of User ID #{$this->id}.");
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

    public function truncatedName()
    {
        return strtoupper(substr($this->first_name, 0, 1)) . '. ' . $this->last_name;
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

    public function sendVerificationCode()
    {
        return PhoneNumberVerifier::sendVerificationCode($this);
    }

    public function markPhoneAsVerified()
    {
        $this->phone_verified_at = Carbon::now();
        return $this->save();
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
        $recentMessagesCount = Message::from($this)->createdAfter(Carbon::parse('-10 minutes'))->count();

        if ($recentMessagesCount <= 50) {
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

    public function getCards()
    {
        if (empty($this->stripe_id)) {
            return collect();
        }

        try {
            $cards = Customer::retrieve($this->stripe_id)->sources->all(['object' => 'card'])->data;
        } catch (Exception $e) {
            Log::error("Unable to list credit cards for User #{$this->id}", $e->getJsonBody() ?? []);
            return collect();
        }

        return collect($cards);
    }

    public function deleteCard(string $cardId)
    {
        $this->clearHasACardCache();

        try {
            Customer::retrieve($this->stripe_id)->sources->retrieve($cardId)->delete();
        } catch (Exception $e) {
            Log::error("Unable to delete credit card #{$cardId} for User #{$this->id}", $e->getJsonBody() ?? []);
            return false;
        }

        if ($card = $this->getCards()->last()) {
            $this->card_last_four = $card->last4;
            $this->card_brand = $card->brand;
        } else {
            $this->card_last_four = null;
            $this->card_brand = null;
        }

        return $this->save();
    }

    public function updateCard(string $cardId, array $cardInfo)
    {
        $this->clearHasACardCache();

        try {
            $card = Customer::retrieve($this->stripe_id)->sources->retrieve($cardId);

            foreach ($cardInfo as $key => $value) {
                $card->$key = $value;
            }

            $card->save();
        } catch (Exception $e) {
            Log::error("Unable to update credit card #{$cardId} for User #{$this->id}", $e->getJsonBody() ?? []);
            return false;
        }

        return $card;
    }

    public function getCard(string $cardId)
    {
        try {
            $card = Customer::retrieve($this->stripe_id)->sources->retrieve($cardId);
        } catch (Exception $e) {
            Log::error("Unable to get credit card #{$cardId} for User #{$this->id}", $e->getJsonBody() ?? []);
            return false;
        }

        return $card;
    }

    public function addCard(string $cardTokenId)
    {
        $this->clearHasACardCache();

        try {
            if (empty($this->stripe_id)) {
                $customer = Customer::create([
                    'email' => $this->email,
                    'source' => $cardTokenId,
                    'metadata' => ['harvey_id' => $this->id],
                ]);
                $this->stripe_id = $customer->id;
            } else {
                $customer = Customer::retrieve($this->stripe_id);
                $card = $customer->sources->create([
                    'source' => $cardTokenId,
                    'metadata' => ['harvey_id' => $this->id],
                ]);
                $customer->default_source = $card->id;
                $customer->save();
            }
            $defaultCard = $customer->sources->retrieve($customer->default_source);
        } catch (Exception $e) {
            Log::error("Unable to add credit card #{$cardTokenId} for User #{$this->id}", $e->getJsonBody() ?? []);
            return false;
        }

        $this->card_last_four = $defaultCard->last4;
        $this->card_brand = $defaultCard->brand;
        $this->save();

        return $defaultCard;
    }

    public function hasACard()
    {
        return Cache::remember("has-a-card-user-id-{$this->id}", TimeInterval::weeks(1)->toMinutes(), function () {
            return $this->getCards()->isNotEmpty();
        });
    }

    public function clearHasACardCache()
    {
        return Cache::forget("has-a-card-user-id-{$this->id}");
    }

    public function sendPasswordResetNotification($token)
    {
        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($this->email)
            ->setTemplate('password.reset')
            ->setTemplateModel(['action_url' => url(config('app.url').route('password.reset', $token, false))]);

        dispatch($transactionalEmailJob);

        return true;
    }
}
