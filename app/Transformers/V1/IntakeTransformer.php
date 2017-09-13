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
        $output = (array) $patient->getIntakeData();
        $output['id'] = $patient->id;

        return $output;
    }
}
