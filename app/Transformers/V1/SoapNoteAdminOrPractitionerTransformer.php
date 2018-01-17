<?php

namespace App\Transformers\V1;

use App\Models\SoapNote;
use League\Fractal\TransformerAbstract;

class SoapNoteAdminOrPractitionerTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(SoapNote $soap_note)
    {
        return [
            'id' => cast_to_string($soap_note->id),
            'assessment' => $soap_note->assessment,
            'created_at' => $soap_note->created_at,
            'created_by_user_id' => cast_to_string($soap_note->creator->id),
            'doctor_name' => $soap_note->doctor_name,
            'notes' => $soap_note->notes,
            'objective' => $soap_note->objective,
            'patient_id' => cast_to_string($soap_note->patient_id),
            'plan' => $soap_note->plan,
            'subjective' => $soap_note->subjective,
        ];
    }
}
