<?php

namespace App\Transformers\V1;

use App\Models\SKU;
use League\Fractal\TransformerAbstract;

class SKUTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(SKU $sku)
    {
        return [
            'id' => (string) $sku->id,
            'item_type' => $sku->item_type,
            'name' => $sku->name,
            'price' => (string) $sku->price,
            'sample' => $sku->labTestInformation->sample,
        ];
    }
}
