<?php

namespace App\Http\Traits;

use App\Models\{Patient, Practitioner, User};

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

    public function scopePatientOrPractitioner($query, User $user)
    {
        return $query->where(function ($query) use ($user)
            {
                $query->where('practitioner_id', $user->practitioner->id ?? 0)
                      ->orWhere('patient_id', $user->patient->id ?? 0);
            });
    }

}
