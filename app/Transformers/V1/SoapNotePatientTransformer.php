<?php

namespace App\Transformers\V1;

use App\Models\SoapNote;
use League\Fractal\TransformerAbstract;

class SoapNotePatientTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(SoapNote $soap_note)
    {
        return [
            'id' => cast_to_string($soap_note->id),
            'created_at' => $soap_note->created_at,
            'created_by_user_id' => cast_to_string($soap_note->creator->id),
            'doctor_name' => $soap_note->doctor_name,
            'plan' => $soap_note->plan,
        ];
    }
}
