<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\LabTestResult;

class LabTestResultTransformer extends HarveyTransformer
{
    /**
     * @param LabTest $labTest
     * @return array
     */
    public function transform(LabTestResult $labTestResult)
    {
        return [
            'id' => cast_to_string($labTestResult->id),
            'created_at' => $labTestResult->created_at,
            'lab_test_id' => cast_to_string($labTestResult->lab_test_id),
            'notes' => $labTestResult->notes,
            'url' => $labTestResult->url,
        ];
    }
}
