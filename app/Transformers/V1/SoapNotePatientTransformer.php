<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\SoapNote;

class SoapNotePatientTransformer extends HarveyTransformer
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
            'patient_id' => cast_to_string($soap_note->patient_id),
            'plan' => $soap_note->plan,
        ];
    }
}
