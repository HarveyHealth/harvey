<?php

namespace App\Http\Controllers\API\alpha\Transformers;

class UserTransformer extends Transformer
{
    public function transform($user)
    {
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'gender' => $user->gender,
            'birthdate' => $user->birthdate,
            'height_feet' => $user->height_feet,
            'height_inches' => $user->height_inches,
            'weight' => $user->weight,
            'user_type' => $user->user_type,
            'payment_info'=> (bool) $user->stripe_customer_id ? true : false,
            'api_token' => $user->api_token,
            'symptoms' => json_decode($user->symptoms)
      ];
    }
}
