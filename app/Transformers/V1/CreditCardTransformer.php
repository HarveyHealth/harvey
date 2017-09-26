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
            'brand' => $card->brand,
            'exp_month' => (string) $card->exp_month,
            'exp_year' => (string) $card->exp_year,
            'funding' => $card->funding,
            'last4' => (string) $card->last4,
            'name' => $card->name,
        ];
    }
}
