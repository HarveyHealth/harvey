<?php

namespace App\Transformers\V1;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['patient', 'practitioner'];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        $appointment = $user->appointments()->ByAppointmentAtDesc()->first();

        return [
            'id' => (string) $user->id,
            'address_1' => $user->address_1,
            'address_2' => $user->address_2,
            'city' => $user->city,
            'created_at' => $user->created_at,
            'doctor_name' => $appointment->practitioner->user->full_name ?? null,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'first_name' => $user->first_name,
            'gender' => $user->gender,
            'has_an_appointment' => (bool) $appointment,
            'image_url' => $user->image_url,
            'last_name' => $user->last_name,
            'phone' => $user->phone,
            'phone_verified_at' => $user->phone_verified_at,
            'state' => $user->state,
            'terms_accepted_at' => $user->terms_accepted_at,
            'user_type' => $user->type,
            'zip' => (string) $user->zip,
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
