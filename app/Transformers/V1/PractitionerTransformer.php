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
            'graduated_year' => $practitioner->graduated_year,
            'license_number' => (string) $practitioner->license_number,
            'license_state' => $practitioner->license_state,
            'license_title' => $practitioner->license_title,
            'name' => $practitioner->user->full_name,
            'picture_url' => $practitioner->picture_url,
            'rate' => (string) $practitioner->rate,
            'school' => $practitioner->school,
            'specialty' => $practitioner->specialty,
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
