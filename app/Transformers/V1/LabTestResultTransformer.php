<?php

namespace App\Transformers\V1;

use App\Models\LabTestResult;
use League\Fractal\TransformerAbstract;

class LabTestResultTransformer extends TransformerAbstract
{
    /**
     * @param LabTest $labTest
     * @return array
     */
    public function transform(LabTestResult $labTestResult)
    {
        return [
            'id' => (string) $labTestResult->id,
            'url' => $labTestResult->url,
            'lab_test_id' => (string) $labTestResult->lab_test_id,
            'notes' => $labTestResult->notes,
        ];
    }
}
