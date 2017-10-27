<?php

namespace App\Models;

use App\Lib\Clients\Typeform;
use App\Lib\TimeInterval;
use Illuminate\Database\Eloquent\{Model, Builder};
use Cache;

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

    public function getIntakeData()
    {
        if (empty($token = $this->intake_token)) {
            return [];
        }

        $key = "intake-token-{$token}-data";

        $output = Cache::remember($key, TimeInterval::weeks(1)->toMinutes(), function () use ($token) {
            $response = json_decode((new Typeform)->get($token)->getBody()->getContents(), true);

            if (empty($response['responses'][0]['token']) || 200 != $response['http_status']) {
                return [];
            }

            return array_intersect_key($response, array_flip(['questions', 'responses']));
        });

        if (empty($output)) {
            Cache::put($key, $output, TimeInterval::hours(3)->toMinutes());
        }

        $output['patient_id'] = $this->id;

        return $output;
    }
}
