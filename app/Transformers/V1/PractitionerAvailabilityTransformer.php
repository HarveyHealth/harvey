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
        $availability_collection = collect(array_values($practitioner->availability));

        $availability = $availability_collection->map(function ($week) {
            return collect($week)->map(function ($timeslot) {
                return [
                    'day' => explode(" ", $timeslot)[0],
                    'time' => explode(" ", $timeslot)[1]
                ];
            });
        });

        return $availability->toArray();
    }
}
