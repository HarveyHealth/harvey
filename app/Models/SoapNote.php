<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Builder};
use Carbon;

class SoapNote extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'created_by_user_id',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enabledUser', function (Builder $builder) {
            return $builder->whereHas('patient.user', function ($query) {
                $query->where('enabled', true);
            });
        });
    }

    /*
     * Relationships
     */

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /*
     * Scopes
     */

    public function scopeFilterForPatient(Builder $builder)
    {
        return $builder->select(['id', 'patient_id', 'created_by_user_id', 'plan']);
    }

    public function scopeBelongingTo(Builder $builder, User $user)
    {
        switch ($user->type) {
            case 'admin':
            case 'practitioner':
                return $builder;
                break;

            case 'patient':
                return $builder->whereHas('patient', function ($builder) use ($user) {
                    $builder->where('patients.user_id', $user->id);
                })->orWhere('created_by_user_id', $user->id)->filterForPatient();
                break;
        }

        return $builder->limit(0);
    }

}
