<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\Prescription;

class PrescriptionTransformer extends HarveyTransformer
{
    /**
     * @return array
     */
    public function transform(Prescription $prescription)
    {
        return [
            'id' => cast_to_string($prescription->id),
            'patient_id' => cast_to_string($prescription->patient_id),
            'created_at' => $prescription->created_at,
            'created_by_user_id' => cast_to_string($prescription->creator->id),
            'doctor_name' => $prescription->doctor_name,
            'notes' => $prescription->notes,
            'url' => $prescription->url,
        ];
    }
}
