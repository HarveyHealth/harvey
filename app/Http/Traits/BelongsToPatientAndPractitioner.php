<?php

namespace App\Http\Traits;

use App\Models\Patient;
use App\Models\Practitioner;

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
}
