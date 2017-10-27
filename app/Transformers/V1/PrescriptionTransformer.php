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
            'id' => (string) $prescription->id,
            'created_by_user_id' => (string) $prescription->creator->id,
            'notes' => $prescription->notes,
            'url' => $prescription->url,
        ];
    }
}
