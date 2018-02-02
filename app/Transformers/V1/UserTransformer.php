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
        return [
            'id' => cast_to_string($user->id),
            'address_1' => $user->address_1,
            'address_2' => $user->address_2,
            'card_brand' => $user->card_brand,
            'card_last4' => cast_to_string($user->card_last_four),
            'city' => $user->city,
            'created_at' => $user->created_at,
            'doctor_name' => $user->getLastPractitioner()->full_name ?? null,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'first_name' => $user->first_name,
            'gender' => $user->gender,
            'has_a_card' => $user->hasACard(),
            'has_an_appointment' => $user->has_an_appointment,
            'image_url' => $user->image_url,
            'last_name' => $user->last_name,
            'phone' => $user->phone,
            'phone_verified_at' => $user->phone_verified_at,
            'state' => $user->state,
            'terms_accepted_at' => $user->terms_accepted_at,
            'timezone' => $user->timezone,
            'user_type' => $user->type,
            'zip' => cast_to_string($user->zip),
            'settings' => array_merge([
                "reminder_email_24_hours" => true,
                "reminder_text_24_hours" => true,
                "reminder_email_1_hour" => true,
                "reminder_text_1_hour" => true,
            ], $user->settings??[]),
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
