<?php

namespace App\Http\Controllers\Webhooks;

use App\Lib\Slack;
use Illuminate\Http\Request;
use Log, ResponseCode;

class StripeController extends BaseWebhookController
{
    public function handle()
    {
        Log::info('New incoming payload on Stripe webhook endpoint.');

        $this->handlePayload(request()->all());

        return response('A-OK!', ResponseCode::HTTP_OK);
    }

    public function handleChargeSucceeded()
    {
        $payload = request()->all();

        Log::info('Stripe charge succeeded: ' . $payload['data']['object']['id']);
    }

    public function handleChargeFailed()
    {
        $payload = request()->all();

        $message = 'Stripe charge failed: ' . $payload['data']['object']['id'];

        // log it
        Log::info($message);

        // slack it
        (new Slack)->notify(new SlackNotification($message, 'operations'));

        $data = [
            'user' => User::userForStripeID($payload['customer'])
        ];
    }

    public function handleCustomerCreated()
    {
        Log::info('Stripe customer created.');
    }

    public function handleCustomerSourceCreated()
    {
        Log::info('Stripe customer payment source created.');
    }

    public function handlePayload(array $payload)
    {
        $methodName = 'handle' . studly_case(str_replace('.', '_', $payload['type']));

        if (!method_exists($this, $methodName)) {
            Slack::it("Stripe webhook method not handled: '{$payload['type']}'", 'engineering');
            return false;
        }

        return $this->$methodName();
    }
}
