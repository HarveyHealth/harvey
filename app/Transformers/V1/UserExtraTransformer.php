<?php

namespace App\Transformers\V1;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserExtraTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        $appointment = $user->appointments()->ByAppointmentAtDesc()->first();

        return [
            'id' => $user->id,
            'created_at' => $user->created_at,
            'doctor_name' => $appointment->practitioner->user->full_name ?? null,
            'email_verified_at' => $user->email_verified_at,
            'has_an_appointment' => (bool) $appointment,
            'phone_verified_at' => $user->phone_verified_at,
            'terms_accepted_at' => $user->terms_accepted_at,
        ];
    }
}
