<?php

namespace App\Http\Controllers\Webhooks;

use App\Events\UserRegistered;
use App\Lib\TimeInterval;
use App\Models\{atient, User};
use Cache, Carbon, ResponseCode;

class ShopifyController extends BaseWebhookController
{
    public function handle()
    {
        $data = request('data');

        $patient = Patient::create(
            'user_id' => User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
            ])->id;
        );

        //event(new UserRegistered($patient->user));

        return response("Thanks!", ResponseCode::HTTP_OK);
    }
}
