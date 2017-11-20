<?php

namespace App\Transformers\V1;

use App\Models\SoapNote;
use League\Fractal\TransformerAbstract;

class SoapNoteTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(SoapNote $soap_note)
    {
        return [
            'id' => cast_to_string($soap_note->id),
            'assessment' => only_if_admin_or_practitioner($soap_note->assessment),
            'created_at' => $soap_note->created_at,
            'created_by_user_id' => cast_to_string($soap_note->creator->id),
            'doctor_name' => $soap_note->doctor_name,
            'objective' => only_if_admin_or_practitioner($soap_note->objective),
            'plan' => $soap_note->plan,
            'subjective' => only_if_admin_or_practitioner($soap_note->subjective),
        ];
    }
}
