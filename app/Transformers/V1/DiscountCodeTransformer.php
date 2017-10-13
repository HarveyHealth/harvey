<?php

namespace App\Transformers\V1;

use App\Models\DiscountCode;
use League\Fractal\TransformerAbstract;

class DiscountCodeTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(DiscountCode $discount_code = null)
    {
        $data = [
            'valid' => !empty($discount_code),
        ];

        if ($data['valid']) {
            $data['id'] = $discount_code->id;
            $data['discount_type'] = $discount_code->discount_type;
            $data['amount'] = $discount_code->amount;
            $data['code'] = $discount_code->code;
        }

        return $data;
    }
}
