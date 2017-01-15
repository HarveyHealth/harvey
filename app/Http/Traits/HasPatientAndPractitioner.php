<?php

namespace App\Http\Traits;

trait HasPatientAndPractitioner
{
    public function patient()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'patient_user_id');
    }

    public function practitioner()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'practitioner_user_id');
    }

    public function scopeForPatient($query, $patient_id)
    {
        return $query->where('patient_user_id', $patient_id);
    }

    public function scopeForPractitioner($query, $practitioner_id)
    {
        return $query->where('practitioner_user_id', $practitioner_id);
    }
}
