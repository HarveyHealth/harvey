<?php

namespace App\Http\Controllers\Webhooks;

use Illuminate\Http\Request;
use Lib\Slack;

class StripeController extends BaseWebhooksController
{
    public function handle() {

        $payload = request()->all();

        \Log::info($payload);

        $method_name = $this->methodForEventName($payload['type']);

        if (method_exists($this, $method_name)) {

            $this->$method_name();

        } else {

            $message = 'Stripe webhook method not handled: ' . $payload['type'];

            // log it
            \Log::info($message);

            // slack it
            (new Slack)->notify(new SlackNotication($message));
        }

        return 'A-OK!';
    }

    public function handleChargeSucceeded() {

        $payload = request()->all();

        \Log::info('Stripe charge succeeded: ' . $payload['data']['object']['id']);
    }

    public function handleChargeFailed() {

        $payload = request()->all();

        \Log::info('Stripe charge failed: ' . $payload['data']['object']['id']);

        $data = [
            'user' => User::userForStripeID($payload['customer'])
        ];
    }

    public function methodForEventName($event_name) {
        return 'handle' . studly_case(str_replace('.','_',$event_name));
    }
}
