<?php

namespace App\Http\Traits;

trait Chargable
{
    public static function userForStripeID($stripe_customer_id)
    {
        return User::where('stripe_customer_id', $stripe_customer_id)
                    ->limit(1)
                    ->get();
    }

    public function charge($amount, $description)
    {
    }

    public function refund($transaction_id, $amount, $reason)
    {
    }
}
