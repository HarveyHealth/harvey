<?php

namespace App\Transformers\V1;

use App\Models\LabTest;
use League\Fractal\TransformerAbstract;

class LabTestTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['patient', 'practitioner', 'lab_order', 'sku', 'results'];

    /**
     * @param LabTest $labTest
     * @return array
     */
    public function transform(LabTest $labTest)
    {
        return [
            'id' => cast_to_string($labTest->id),
            'completed_at' => $labTest->completed_at,
            'lab_order_id' => cast_to_string($labTest->lab_order_id),
            'shipment_code' => cast_to_string($labTest->shipment_code),
            'sku_id' => cast_to_string($labTest->sku_id),
            'status' => $labTest->status,
        ];
    }

    /**
     * @param LabTest $labTest
     * @return mixed
     */
    public function includePatient(LabTest $labTest)
    {
        return $this->item($labTest->patient, new PatientTransformer())->setResourceKey('patient');
    }

    /**
     * @param LabTest $labTest
     * @return mixed
     */
    public function includePractitioner(LabTest $labTest)
    {
        return $this->item($labTest->practitioner, new PractitionerTransformer())->setResourceKey('practitioner');
    }

    /**
     * @param LabTest $labTest
     * @return mixed
     */
    public function includeLabOrder(LabTest $labTest)
    {
        return $this->item($labTest->labOrder, new LabOrderTransformer())->setResourceKey('lab_order');
    }

    /**
     * @param LabTest $labTest
     * @return mixed
     */
    public function includeSku(LabTest $labTest)
    {
        return $this->item($labTest->sku, new SKUTransformer())->setResourceKey('sku');
    }

    /**
     * @param LabTest $labTest
     * @return mixed
     */
    public function includeResults(LabTest $labTest)
    {
        return $this->collection($labTest->results, new LabTestResultTransformer(), 'lab_test_result');
    }
}
