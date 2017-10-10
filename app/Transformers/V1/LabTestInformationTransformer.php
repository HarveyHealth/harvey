<?php

namespace App\Transformers\V1;

use App\Models\LabTestInformation;
use League\Fractal\TransformerAbstract;

class LabTestInformationTransformer extends TransformerAbstract
{
    /**
     * @param LabTestInformation $labTestInformation
     * @return array
     */
    public function transform(LabTestInformation $labTestInformation)
    {
        return [
            'id' => $labTestInformation->id,
            'description' => $labTestInformation->description,
            'image' => $labTestInformation->image,
            'item_type' => $labTestInformation->sku->item_type,
            'lab_name' => $labTestInformation->lab_name,
            'name' => $labTestInformation->sku->name,
            'price' => $labTestInformation->sku->price,
            'quote' => $labTestInformation->quote,
            'sample' => $labTestInformation->sample,
            'visibility' => $labTestInformation->visibility,
        ];
    }
}
