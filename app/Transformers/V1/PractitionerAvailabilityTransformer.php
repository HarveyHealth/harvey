<?php

namespace App\Transformers\V1;

use App\Models\Practitioner;
use League\Fractal\TransformerAbstract;

class PractitionerAvailabilityTransformer extends TransformerAbstract
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
