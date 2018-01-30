<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\SKU;

class SKUTransformer extends HarveyTransformer
{
    public function transform(SKU $sku)
    {
        return [
            'id' => cast_to_string($sku->id),
            'cost' => cast_to_string($sku->cost),
            'item_type' => $sku->item_type,
            'lab_test_information' => $sku->labTestInformation ?? null,
            'name' => $sku->name,
            'price' => cast_to_string($sku->price),
            'cost' => cast_to_string($sku->cost),
            'lab_test_information' => $sku->labTestInformation ?? null,
        ];
    }
}
