<?php

namespace App\Transformers\V1;

use App\Models\LabOrder;
use League\Fractal\TransformerAbstract;

class LabOrderTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['patient', 'practitioner', 'lab_tests'];

    /**
     * @param LabOrder $labOrder
     * @return array
     */
    public function transform(LabOrder $labOrder)
    {
        return [
            'id' => (string) $labOrder->id,
            'patient_id' => (string) $labOrder->patient_id,
            'practitioner_id' => (string) $labOrder->practitioner_id,
            'status' => $labOrder->status,
            'shipment_code' => (string) $labOrder->shipment_code,
            'completed_at' => $labOrder->completed_at,
        ];
    }

    /**
     * @param LabOrder $labOrder
     * @return mixed
     */
    public function includePatient(LabOrder $labOrder)
    {
        return $this->item($labOrder->patient, new PatientTransformer())->setResourceKey('patients');
    }

    /**
     * @param LabOrder $labOrder
     * @return mixed
     */
    public function includePractitioner(LabOrder $labOrder)
    {
        return $this->item($labOrder->practitioner, new PractitionerTransformer())->setResourceKey('practitioners');
    }

    /**
     * @param LabOrder $labOrder
     * @return mixed
     */
    public function includeLabTests(LabOrder $labOrder)
    {
        return $this->collection($labOrder->labTests, new LabTestTransformer())->setResourceKey('lab_tests');
    }
}
