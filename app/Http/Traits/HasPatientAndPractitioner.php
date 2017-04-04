<?php

namespace App\Http\Traits;

trait HasPatientAndPractitioner
{
    public function scopeForPatient($query, $patient_id)
    {
        return $query->where('patient_user_id', $patient_id)->with('patient');
    }

    public function scopeForPractitioner($query, $practitioner_id)
    {
        return $query->where('practitioner_user_id', $practitioner_id)->with('practitioner');
    }
}
