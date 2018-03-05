<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\Patient;

class IntakeTransformer extends HarveyTransformer
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
