<?php

namespace App\Http\Traits;

use App\Models\{Patient, Practitioner, User};
use Illuminate\Database\Eloquent\Builder;

trait BelongsToPatientAndPractitioner
{
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }

    public function scopePatientOrPractitioner(Builder $builder, User $user)
    {
        return $builder->where(function ($builder) use ($user) {
                $builder->where('practitioner_id', $user->practitioner->id ?? 0)
                      ->orWhere('patient_id', $user->patient->id ?? 0);
        });
    }
}
