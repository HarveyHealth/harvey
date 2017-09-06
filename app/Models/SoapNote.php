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
}
