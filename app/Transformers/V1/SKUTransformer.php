<?php

namespace App\Transformers\V1;

use App\Models\SKU;
use League\Fractal\TransformerAbstract;

class SKUTransformer extends TransformerAbstract
{
    public function transform(SKU $sku)
    {
        return [
            'id' => (string) $sku->id,
            'name' => $sku->name,
            'item_type' => $sku->item_type,
            'price' => (string) $sku->price,
            'cost' => (string) $sku->cost,
            'lab_test_information' => $sku->labTestInformation ?? null,
        ];
    }
}
