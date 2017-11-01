<?php

namespace App\Transformers\V1;

use App\Models\SoapNote;
use League\Fractal\TransformerAbstract;

class SoapNoteTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(SoapNote $soapNote)
    {
        return [
            'id' => cast_to_string($soapNote->id),
            'assessment' => $soapNote->assessment,
            'created_at' => $soapNote->created_at,
            'created_by_user_id' => cast_to_string($soapNote->creator->id),
            'doctor_name' => $soapNote->doctor_name,
            'objective' => $soapNote->objective,
            'plan' => $soapNote->plan,
            'subjective' => $soapNote->subjective,
        ];
    }
}
