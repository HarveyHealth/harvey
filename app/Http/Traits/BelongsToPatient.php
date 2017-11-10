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

    public function getDoctorNameAttribute()
    {
        $builder = $this->patient->appointments()->withoutGlobalScopes()->beforeThan($this->created_at)->byAppointmentAtDesc();

        return Cache::remember("practitioner_name_user_id_{$this->patient->user->id}_at_{$this->created_at}", TimeInterval::weeks(2)->addMinutes(rand(0, 120))->toMinutes(), function () use ($builder) {
            return $builder->first()->practitioner->user->full_name ?? false;
        });
    }
}
