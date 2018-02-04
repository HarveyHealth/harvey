<?php

namespace App\Models;

use App\Events\CreditCardUpdated;
use App\Http\Interfaces\Mailable;
use App\Http\Traits\{IsNot, Textable};
use App\Lib\{PhoneNumberVerifier, TimeInterval, TransactionalEmail, ZipCodeValidator};
use App\Mail\VerifyEmailAddress;
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

    const ADDRESS_ATTRIBUTES = [
        'address_1',
        'address_2',
        'city',
        'state',
        'zip',
    ];

    public $asYouType = true;

    public $allowedSortBy = [
        'id',
        'created_at',
        'email',
        'first_name',
        'last_name',
        'terms_accepted_at',
    ];

    protected $guarded = [
        'id',
        'card_brand',
        'card_last_four',
        'created_at',
        'email_verified_at',
        'enabled',
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
        'phone_verified_at',
        'updated_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'settings' => 'array'
    ];

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
        if ($this->isPatient()) {
            return 'patient';
        } elseif ($this->isPractitioner()) {
            return 'practitioner';
        } elseif ($this->isAdmin()) {
            return 'admin';
        }

        throw new Exception("Unable to determine type of User ID #{$this->id}.");
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

    public function getTruncatedNameAttribute()
    {
        return strtoupper(substr($this->first_name, 0, 1)) . '. ' . $this->last_name;
    }

    public function getHasAnAppointmentAttribute()
    {
        $builder = $this->appointments();

        return Cache::remember("has_an_appointment_user_id_{$this->id}", TimeInterval::weeks(2)->addMinutes(rand(0, 120))->toMinutes(), function () use ($builder) {
            return (bool) $builder->first();
        });
    }

    public function getIntercomHashAttribute() {
        return hash_hmac('sha256', $this->id, config('services.intercom.key'));
    }

    public function clearHasAnAppointmentCache()
    {
        return Cache::forget("has_an_appointment_user_id_{$this->id}");
    }

    public function getLastPractitioner()
    {
        if ($this->isNotPatient()) {
            return null;
        }

        $builder = $this->appointments()->ByAppointmentAtDesc();

        return Cache::remember("last_practitioner_user_id_{$this->id}", TimeInterval::weeks(2)->addMinutes(rand(0, 120))->toMinutes(), function () use ($builder) {
            return $builder->first()->practitioner->user ?? false;
        });
    }

    public function clearLastPractitionerCache()
    {
        return Cache::forget("last_practitioner_user_id_{$this->id}");
    }

    public function clearAppointmentsCache()
    {
        $this->clearHasAnAppointmentCache();
        $this->clearLastPractitionerCache();

        return true;
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

    public function intake()
    {
        return $this->hasOne(Intake::class);
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
        return (bool) $this->nextUpcomingAppointment();
    }

    public function isPatient()
    {
        $builder = $this->patient();
        return Cache::remember("is_patient_user_id_{$this->id}", TimeInterval::months(1)->toMinutes(), function () use ($builder) {
            return (bool) $builder->first();
        });
    }

    public function isPractitioner()
    {
        $builder = $this->practitioner();
        return Cache::remember("is_practitioner_user_id_{$this->id}", TimeInterval::months(1)->toMinutes(), function () use ($builder) {
            return (bool) $builder->first();
        });
    }

    public function isAdmin()
    {
        $builder = $this->admin();
        return Cache::remember("is_admin_user_id_{$this->id}", TimeInterval::months(1)->toMinutes(), function () use ($builder) {
            return (bool) $builder->first();
        });
    }

    public function isAdminOrPractitioner()
    {
        return $this->isAdmin() || $this->isPractitioner();
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
        if (empty($stripe_id = $this->stripe_id)) {
            return collect();
        }

        $cache_key = "get-cards-user-id-{$this->id}";

        $cards = Cache::remember($cache_key, TimeInterval::days(rand(15, 30))->addMinutes(rand(0, 120))->toMinutes(), function () use ($stripe_id) {
            try {
                $cards = Customer::retrieve($stripe_id)->sources->all(['object' => 'card'])->data;
            } catch (Exception $e) {
                Log::error("Unable to list credit cards for User #{$this->id}", ['exception_message' => $e->getMessage()]);
                return null;
            }

            return collect($cards);
        });

        if (is_null($cards)) {
            Cache::put($cache_key, $cards = [], TimeInterval::hours(1)->toMinutes());
        }

        return collect($cards);
    }

    public function clearGetCardsCache()
    {
        return Cache::forget("get-cards-user-id-{$this->id}");
    }

    public function deleteCard(string $cardId)
    {
        $this->clearGetCardsCache();

        try {
            Customer::retrieve($this->stripe_id)->sources->retrieve($cardId)->delete();
        } catch (Exception $e) {
            Log::error("Unable to delete credit card #{$cardId} for User #{$this->id}", ['exception_message' => $e->getMessage()]);
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
        $this->clearGetCardsCache();

        try {
            $card = Customer::retrieve($this->stripe_id)->sources->retrieve($cardId);

            foreach ($cardInfo as $key => $value) {
                $card->$key = $value;
            }

            $card->save();
        } catch (Exception $e) {
            Log::error("Unable to update credit card #{$cardId} for User #{$this->id}", ['exception_message' => $e->getMessage()]);
            return false;
        }

        event(new CreditCardUpdated($this));

        return $card;
    }

    public function getCard(string $cardId)
    {
        try {
            $card = Customer::retrieve($this->stripe_id)->sources->retrieve($cardId);
        } catch (Exception $e) {
            Log::error("Unable to get credit card #{$cardId} for User #{$this->id}", ['exception_message' => $e->getMessage()]);
            return false;
        }

        return $card;
    }

    public function addCard(string $cardTokenId)
    {
        $this->clearGetCardsCache();

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
            Log::error("Unable to add credit card #{$cardTokenId} for User #{$this->id}", ['exception_message' => $e->getMessage()]);
            return false;
        }

        $this->card_last_four = $defaultCard->last4;
        $this->card_brand = $defaultCard->brand;
        $this->save();

        event(new CreditCardUpdated($this));

        return $defaultCard;
    }

    public function hasACard()
    {
        return $this->getCards()->isNotEmpty();
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
