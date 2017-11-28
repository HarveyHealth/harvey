<?php

namespace App\Transformers\V1;

use App\Models\Patient;
use League\Fractal\TransformerAbstract;

class IntakeTransformer extends TransformerAbstract
{
    /**
     * @param Patient $patient
     * @return array
     */
    public function transform(array $intake)
    {
        $intake['id'] = $intake['responses'][0]['token'] ?? null;

        return $intake;
    }
}
