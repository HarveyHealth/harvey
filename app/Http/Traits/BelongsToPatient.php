<?php

namespace App\Http\Traits;

use App\Models\Patient;
use App\Lib\TimeInterval;
use Illuminate\Database\Eloquent\Builder;
use Cache;

trait BelongsToPatient
{
    public function patient()
    {
        return $this->belongsTo(Patient::class);
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
                })->orWhere('created_by_user_id', $user->id);
                break;
        }

        return $builder->limit(0);
    }

    public function getDoctorNameAttribute() {

        $builder = $this->patient->appointments()->withoutGlobalScopes()->beforeThan($this->created_at)->byAppointmentAtDesc();

        return Cache::remember("practitioner_name_user_id_{$this->patient->user->id}_at_{$this->created_at}", TimeInterval::weeks(2)->addMinutes(rand(0, 120))->toMinutes(), function () use ($builder) {
            return $builder->first()->practitioner->user->full_name ?? false;
        });
    }
}
