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
            'id' => cast_to_string($discount_code->code),
            'discount_type' => $discount_code->discount_type,
            'amount' => cast_to_string($discount_code->amount),
            'code' => cast_to_string($discount_code->code),
        ];
    }
}
