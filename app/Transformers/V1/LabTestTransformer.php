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
            'id' => (string) $labTest->id,
            'lab_order_id' => (string) $labTest->lab_order_id,
            'sku_id' => (string) $labTest->sku_id,
            'status' => $labTest->status,
            'shipment_code' => (string) $labTest->shipment_code,
            'completed_at' => $labTest->completed_at,
        ];
    }

    /**
     * @param LabTest $labTest
     * @return mixed
     */
    public function includePatient(LabTest $labTest)
    {
        return $this->item($labTest->patient, new PatientTransformer())->setResourceKey('patients');
    }

    /**
     * @param LabTest $labTest
     * @return mixed
     */
    public function includePractitioner(LabTest $labTest)
    {
        return $this->item($labTest->practitioner, new PractitionerTransformer())->setResourceKey('practitioners');
    }

    /**
     * @param LabTest $labTest
     * @return mixed
     */
    public function includeLabOrder(LabTest $labTest)
    {
        return $this->item($labTest->labOrder, new LabOrderTransformer())->setResourceKey('lab_orders');
    }

    /**
     * @param LabTest $labTest
     * @return mixed
     */
    public function includeSku(LabTest $labTest)
    {
        return $this->item($labTest->sku, new SKUTransformer())->setResourceKey('SKUs');
    }

    /**
     * @param LabTest $labTest
     * @return mixed
     */
    public function includeResults(LabTest $labTest)
    {
        return $this->collection($labTest->results, new LabTestResultTransformer(), 'lab_tests_results');
    }
}
