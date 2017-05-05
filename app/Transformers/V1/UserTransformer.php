<?php

namespace App\Transformers\V1;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'gender' => $user->gender,
            'id' => (string) $user->id,
            'image_url' => $user->imageURL(),
            'last_name' => $user->last_name,
            'phone' => $user->phone,
            'user_type' => $user->userType(),
            'zip' => $user->zip,
        ];
    }
}
