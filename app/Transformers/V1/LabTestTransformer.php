<?php

namespace App\Transformers\V1;

use App\Models\LabTest;
use League\Fractal\TransformerAbstract;

class LabTestTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['patient', 'practitioner', 'lab_order'];

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
            'results_url' => $labTest->results_url,
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
}
