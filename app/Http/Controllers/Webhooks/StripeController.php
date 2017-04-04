<?php

namespace App\Http\Controllers\Webhooks;

use Illuminate\Http\Request;
use App\Lib\Slack;
use App\Notifications\SlackNotification;

class StripeController extends BaseWebhookController
{
    public function handle()
    {
        $payload = request()->all();

        \Log::info("\n\n\n");
        \Log::info($payload['type']);
        \Log::info($payload);

        $method_name = $this->methodForEventName($payload['type']);

        if (method_exists($this, $method_name)) {
            $this->$method_name();
        } else {
            $message = 'Stripe webhook method not handled: ' . $payload['type'];

            // log it
            \Log::info($message);

            // slack it
            (new Slack)->notify(new SlackNotification($message, 'engineering'));
        }

        return 'A-OK!';
    }

    public function handleChargeSucceeded()
    {
        $payload = request()->all();

        \Log::info('Stripe charge succeeded: ' . $payload['data']['object']['id']);
    }

    public function handleChargeFailed()
    {
        $payload = request()->all();

        $message = 'Stripe charge failed: ' . $payload['data']['object']['id'];

        // log it
        \Log::info($message);

        // slack it
        (new Slack)->notify(new SlackNotification($message, 'business'));

        $data = [
            'user' => User::userForStripeID($payload['customer'])
        ];
    }

    public function handleCustomerCreated()
    {
        \Log::info('Stripe customer created.');
    }

    public function handleCustomerSourceCreated()
    {
        \Log::info('Stripe customer payment source created.');
    }

    public function methodForEventName($event_name)
    {
        return 'handle' . studly_case(str_replace('.', '_', $event_name));
    }
}
