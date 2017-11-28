<?php

namespace App\Transformers\V1;

use App\Models\Practitioner;
use League\Fractal\TransformerAbstract;

class PractitionerTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user'];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Practitioner $practitioner)
    {
        return [
            'id' => cast_to_string($practitioner->id),
            'background_picture_url' => $practitioner->background_picture_url,
            'description' => $practitioner->description,
            'graduated_year' => $practitioner->graduated_year,
            'licenses' => $practitioner->licenses,
            'name' => $practitioner->user->full_name,
            'picture_url' => $practitioner->picture_url,
            'school' => $practitioner->school,
            'specialty' => json_decode($practitioner->specialty) ?? [],
            'type_name' => $practitioner->type->name,
            'user_id' => cast_to_string($practitioner->user_id),
        ];
    }

    /**
     * @param Practitioner $practitioner
     * @return mixed
     */
    public function includeUser(Practitioner $practitioner)
    {
        return $this->item($practitioner->user, new UserTransformer())->setResourceKey('user');
    }
}
