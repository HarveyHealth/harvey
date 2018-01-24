<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\Practitioner;

class PractitionerAvailabilityTransformer extends HarveyTransformer
{
    /**
     * @param Practitioner $practitioner
     * @return static
     */
    public function transform(Practitioner $practitioner)
    {
        return $practitioner->availability;
    }
}
