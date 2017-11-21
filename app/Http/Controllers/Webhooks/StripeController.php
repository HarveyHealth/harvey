<?php

namespace App\Http\Controllers\Webhooks;

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

    public function handleCustomerUpdated()
    {
        Log::info('Stripe customer updated.');
    }

    public function handleCustomerSourceCreated()
    {
        Log::info('Stripe customer payment source created.');
    }

    public function handleCustomerSourceDeleted()
    {
        Log::info('Stripe customer payment source deleted.');
    }

    public function handlePayoutCreated()
    {
        Log::info('Stripe payout created.');
    }

    public function handlePayoutPaid()
    {
        Log::info('Stripe payout paid.');
    }

    public function handleBalanceAvailable()
    {
        Log::info('Stripe balance available.');
    }

    public function handleChargeRefunded()
    {
        Log::info('Stripe charge refunded.');
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
