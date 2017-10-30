<?php

namespace App\Transformers\V1;

use App\Models\Prescription;
use League\Fractal\TransformerAbstract;

class PrescriptionTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Prescription $prescription)
    {
        return [
            'id' => cast_to_string($prescription->id),
            'created_at' => $prescription->created_at,
            'created_by_user_id' => cast_to_string($prescription->creator->id),
            'doctor_name' => $prescription->doctor_name,
            'notes' => $prescription->notes,
            'url' => $prescription->url,
        ];
    }
}
