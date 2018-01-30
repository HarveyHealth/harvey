<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\LabTestInformation;

class LabTestInformationTransformer extends HarveyTransformer
{
    /**
     * @param LabTestInformation $labTestInformation
     * @return array
     */
    public function transform(LabTestInformation $labTestInformation)
    {
        return [
            'id' => cast_to_string($labTestInformation->id),
            'description' => $labTestInformation->description,
            'example' => $labTestInformation->example,
            'image' => $labTestInformation->image,
            'item_type' => $labTestInformation->sku->item_type,
            'lab_name' => $labTestInformation->lab_name,
            'name' => $labTestInformation->sku->name,
            'price' => cast_to_string($labTestInformation->sku->price),
            'quote' => $labTestInformation->quote,
            'sample' => $labTestInformation->sample,
            'sku_id' => cast_to_string($labTestInformation->sku->id),
            'visibility' => $labTestInformation->visibility,
        ];
    }
}
