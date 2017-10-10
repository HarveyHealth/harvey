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
    public function transform(Patient $patient)
    {
        $output = $patient->getIntakeData();
        $output['id'] = $output['responses'][0]->token;

        return $output;
    }
}
