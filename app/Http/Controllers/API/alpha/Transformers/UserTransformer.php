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
            'user_type' => $user->user_type,
            'phone' => $user->phone,
            'payment_info'=> (bool) $user->stripe_customer_id ? true : false,
            'api_token' => $user->api_token
      ];
    }
}
