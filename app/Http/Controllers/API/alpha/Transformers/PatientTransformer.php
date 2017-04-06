<?php

namespace App\Http\Controllers\API\alpha\Transformers;

class PatientTransformer extends Transformer
{
    public function transform($user)
    {
        return [
            'id' => $user->id,
      ];
    }
}
