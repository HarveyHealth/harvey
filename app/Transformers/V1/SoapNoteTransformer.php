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
            'created_by_user_id' => (string) $soapNote->creator->id,
            'subjective' => $soapNote->subjective,
            'objective' => $soapNote->objective,
            'assessment' => $soapNote->assessment,
            'plan' => $soapNote->plan,
        ];
    }
}
