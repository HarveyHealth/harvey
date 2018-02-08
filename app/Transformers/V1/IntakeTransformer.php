<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\Intake;

class IntakeTransformer extends HarveyTransformer
{
    /**
     * @param Intake $intake
     * @return array
     */
    public function transform(Intake $intake)
    {
        $output = $intake->data;
        $output['id'] = $output['responses'][0]['token'] ?? null;
        $output['notes'] = $intake->notes;

        return $output;
    }
}
