<?php

namespace App\Http\Traits;

trait HasPatientAndPractitioner
{
    public function patient()
    {
        return $this->hasOne(\App\Models\User::class,'id','patient_user_id');
    }

    public function practitioner()
    {
        return $this->hasOne(\App\Models\User::class,'id','practitioner_user_id');
    }
}
