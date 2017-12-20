<?php

namespace App\Transformers\V1;

use App\Models\SKU;
use League\Fractal\TransformerAbstract;

class SKUTransformer extends TransformerAbstract
{
    public function transform(SKU $sku)
    {
        return [
            'id' => cast_to_string($sku->id),
            'item_type' => $sku->item_type,
            'name' => $sku->name,
            'price' => cast_to_string($sku->price),
            'cost' => cast_to_string($sku->cost),
            'lab_test_information' => $sku->labTestInformation ?? null,
        ];
    }
}
