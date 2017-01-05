<?php

namespace App\Http\Traits;

trait Chargable
{
    public static function userForStripeID($stripe_customer_id) {

        return User::where('stripe_customer_id', $stripe_customer_id)
                    ->limit(1)
                    ->get();
    }

    function charge($amount, $description) {

    }

    function refund($transaction_id, $amount, $reason) {

    }
}
