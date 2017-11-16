<?php

namespace App\Transformers\V1;

use Stripe\Card;
use League\Fractal\TransformerAbstract;

class CreditCardTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Card $card)
    {
        return [
            'id' => $card->id,
            'address_city' => $card->address_city,
            'address_country' => $card->address_country,
            'address_line1' => $card->address_line1,
            'address_line2' => $card->address_line2,
            'address_state' => $card->address_state,
            'address_zip' => $card->address_zip,
            'brand' => $card->brand,
            'country' => $card->country,
            'exp_month' => (string) $card->exp_month,
            'exp_year' => (string) $card->exp_year,
            'funding' => $card->funding,
            'last4' => (string) $card->last4,
            'name' => $card->name,
        ];
    }
}
