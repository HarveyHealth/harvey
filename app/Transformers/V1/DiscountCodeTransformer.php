<?php

namespace App\Transformers\V1;

use App\Models\DiscountCode;
use League\Fractal\TransformerAbstract;

class DiscountCodeTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(DiscountCode $discount_code)
    {
        return [
            'valid' => true,
            'id' => $discount_code->code,
            'discount_type' => $discount_code->discount_type,
            'amount' => $discount_code->amount,
            'code' => $discount_code->code,
        ];
    }
}
