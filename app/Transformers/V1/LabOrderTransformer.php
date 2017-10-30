<?php

namespace App\Transformers\V1;

use App\Models\LabOrder;
use League\Fractal\TransformerAbstract;

class LabOrderTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['patient', 'practitioner', 'lab_tests', 'invoice'];

    /**
     * @param LabOrder $labOrder
     * @return array
     */
    public function transform(LabOrder $labOrder)
    {
        return [
            'id' => cast_to_string($labOrder->id),
            'address_1' => $labOrder->address_1,
            'address_2' => $labOrder->address_2,
            'city' => $labOrder->city,
            'completed_at' => $labOrder->completed_at,
            'created_at' => $labOrder->created_at,
            'patient_id' => cast_to_string($labOrder->patient_id),
            'practitioner_id' => cast_to_string($labOrder->practitioner_id),
            'shipment_code' => cast_to_string($labOrder->shipment_code),
            'shipment_label_url' => $labOrder->shipment_label_url,
            'shippo_id' => $labOrder->shippo_id,
            'state' => $labOrder->state,
            'status' => $labOrder->status,
            'zip' => cast_to_string($labOrder->zip),
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

    /**
     * @param LabOrder $labOrder
     * @return mixed
     */
    public function includeInvoice(LabOrder $labOrder)
    {
        if ($invoice = $labOrder->invoice) {
            return $this->item($invoice, new InvoiceTransformer(), 'invoices');
        }
    }
}
