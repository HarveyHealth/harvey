<?php

namespace App\Http\Controllers\API\alpha\Transformers;

class UserTransformer extends Transformer
{
    public function transform($user)
    {
        return [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name
      ];
    }
}
