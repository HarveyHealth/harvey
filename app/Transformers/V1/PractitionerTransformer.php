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
            'background_picture_url' => $practitioner->background_picture_url,
            'description' => $practitioner->description,
            'graduated_at' => $practitioner->graduated_at,
            'id' => (string) $practitioner->id,
            'license_number' => $practitioner->license->number,
            'license_state' => $practitioner->license->state,
            'name' => $practitioner->user->fullName(),
            'picture_url' => $practitioner->picture_url,
            'school' => $practitioner->school,
            'specialty' => $practitioner->specialty,
            'type_name' => $practitioner->type->name,
            'user_id' => (string) $practitioner->user_id,
        ];
    }

    /**
     * @param Practitioner $practitioner
     * @return mixed
     */
    public function includeUser(Practitioner $practitioner)
    {
        return $this->item($practitioner->user, new UserTransformer())->setResourceKey('users');
    }
}
