<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Builder};
use Laravel\Scout\Searchable;

class Patient extends Model
{
    use Searchable;

    protected $guarded = ['id', 'enabled', 'user_id', 'stripe_customer_id',
                            'stripe_expiry_month', 'stripe_expiry_year',
                            'stripe_brand', 'stripe_last_four',
                            'created_at', 'updated_at'];

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

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'email' => $this->user->email,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'full_name' => $this->user->full_name,
       ];
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function notes()
    {
        return $this->hasMany(PatientNote::class, 'patient_id', 'id');
    }

    public function chartNotes()
    {
        return $this->hasMany(ChartNote::class, 'patient_id', 'id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'id');
    }

    public function test()
    {
        return $this->hasMany(Test::class, 'patient_id', 'id');
    }
}
