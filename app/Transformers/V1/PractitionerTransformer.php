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
            'id' => (string) $practitioner->id,
            'background_picture_url' => $practitioner->background_picture_url,
            'description' => $practitioner->description,
            'graduated_at' => $practitioner->graduated_at,
            'license_title' => $practitioner->license->title,
            'license_number' => (string) $practitioner->license->number,
            'license_state' => $practitioner->license->state,
            'name' => $practitioner->user->full_name,
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
