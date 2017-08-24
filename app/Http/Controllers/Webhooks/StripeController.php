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

        Log::info("Stripe charge succeeded: {$payload['data']['object']['id']}");
    }

    public function handleChargeFailed()
    {
        $payload = request()->all();

        $message = "Stripe charge failed: {$payload['data']['object']['id']}";

        Log::info($message);

        ops_warning('Webhook warning!', $message, 'operations');
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
            ops_warning('Stripe webhook method not handled', "Type: {$payload['type']}", 'engineering');
            return false;
        }

        return $this->$methodName();
    }
}
