<?php

namespace App\Transformers\V1;

use App\Models\Practitioner;
use League\Fractal\TransformerAbstract;

class PractitionerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Practitioner $practitioner)
    {
        return [
            'id' => $practitioner->id
        ];
    }
}
