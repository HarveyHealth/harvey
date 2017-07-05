<?php

namespace App\Transformers\V1;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'patient', 'practitioner'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'address_1' => $user->address_1,
            'address_2' => $user->address_2,
            'city' => $user->city,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'gender' => $user->gender,
            'id' => (string) $user->id,
            'image_url' => $user->image_url,
            'last_name' => $user->last_name,
            'phone' => $user->phone,
            'state' => $user->state,
            'timezone' => $user->timezone,
            'user_type' => $user->type,
            'zip' => $user->zip,
        ];
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function includePatient(User $user)
    {
        if ($patient = $user->patient) {
            return $this->item($patient, new PatientTransformer())->setResourceKey('patient');
        }
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function includePractitioner(User $user)
    {
        if ($practitioner = $user->practitioner) {
            return $this->item($practitioner, new PractitionerTransformer())->setResourceKey('practitioner');
        }
    }
}
