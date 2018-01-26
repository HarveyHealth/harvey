<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use Stripe\Card;

class CreditCardTransformer extends HarveyTransformer
{
    /**
     * @return array
     */
    public function transform(Card $card)
    {
        return [
            'id' => cast_to_string($card->id),
            'address_city' => $card->address_city,
            'address_country' => $card->address_country,
            'address_line1' => $card->address_line1,
            'address_line2' => $card->address_line2,
            'address_state' => $card->address_state,
            'address_zip' => cast_to_string($card->address_zip),
            'brand' => $card->brand,
            'country' => $card->country,
            'exp_month' => cast_to_string($card->exp_month),
            'exp_year' => cast_to_string($card->exp_year),
            'funding' => $card->funding,
            'last4' => cast_to_string($card->last4),
            'name' => $card->name,
        ];
    }
}
