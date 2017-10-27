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
            'id' => (string) $soapNote->id,
            'created_at' => $soapNote->created_at,
            'created_by_user_id' => (string) $soapNote->creator->id,
            'doctor_name' => $soapNote->doctor_name,
            'subjective' => $soapNote->subjective,
            'objective' => $soapNote->objective,
            'assessment' => $soapNote->assessment,
            'plan' => $soapNote->plan,
        ];
    }
}
