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
            'completed_at' => $labOrder->completed_at,
            'created_at' => $labOrder->created_at,
            'id' => (string) $labOrder->id,
            'patient_id' => (string) $labOrder->patient_id,
            'practitioner_id' => (string) $labOrder->practitioner_id,
            'shipment_code' => (string) $labOrder->shipment_code,
            'status' => $labOrder->status,
        ];
    }

    /**
     * @param LabOrder $labOrder
     * @return mixed
     */
    public function includePatient(LabOrder $labOrder)
    {
        $transformer = new PatientTransformer();

        if (in_array('user', $this->getCurrentScope()->getManager()->getRequestedIncludes())) {
            $transformer->setDefaultIncludes(['user']);
        }

        return $this->item($labOrder->patient, $transformer, 'patients');
    }

    /**
     * @param LabOrder $labOrder
     * @return mixed
     */
    public function includePractitioner(LabOrder $labOrder)
    {
        $transformer = new PractitionerTransformer();

        if (in_array('user', $this->getCurrentScope()->getManager()->getRequestedIncludes())) {
            $transformer->setDefaultIncludes(['user']);
        }

        return $this->item($labOrder->practitioner, $transformer, 'practitioners');
    }

    /**
     * @param LabOrder $labOrder
     * @return mixed
     */
    public function includeLabTests(LabOrder $labOrder)
    {
        return $this->collection($labOrder->labTests, new LabTestTransformer(), 'lab_tests');
    }
}
