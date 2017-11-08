<?php

namespace App\Transformers\V1;

use App\Models\LabOrder;
use League\Fractal\TransformerAbstract;

class LabOrderTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['patient', 'practitioner', 'lab_tests', 'invoice', 'discount_code'];

    /**
     * @param LabOrder $lab_order
     * @return array
     */
    public function transform(LabOrder $lab_order)
    {
        return [
            'id' => cast_to_string($labOrder->id),
            'address_1' => $labOrder->address_1,
            'address_2' => $labOrder->address_2,
            'city' => $labOrder->city,
            'completed_at' => $labOrder->completed_at,
            'created_at' => $labOrder->created_at,
            'discount_code_id' => cast_to_string($lab_order->discount_code_id),
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
     * @param LabOrder $lab_order
     * @return mixed
     */
    public function includePatient(LabOrder $lab_order)
    {
        $transformer = new PatientTransformer();

        if (in_array('user', $this->getCurrentScope()->getManager()->getRequestedIncludes())) {
            $transformer->setDefaultIncludes(['user']);
        }

        return $this->item($lab_order->patient, $transformer, 'patients');
    }

    /**
     * @param LabOrder $lab_order
     * @return mixed
     */
    public function includePractitioner(LabOrder $lab_order)
    {
        $transformer = new PractitionerTransformer();

        if (in_array('user', $this->getCurrentScope()->getManager()->getRequestedIncludes())) {
            $transformer->setDefaultIncludes(['user']);
        }

        return $this->item($lab_order->practitioner, $transformer, 'practitioners');
    }

    /**
     * @param LabOrder $lab_order
     * @return mixed
     */
    public function includeLabTests(LabOrder $lab_order)
    {
        return $this->collection($lab_order->labTests, new LabTestTransformer(), 'lab_tests');
    }

    /**
     * @param LabOrder $lab_order
     * @return mixed
     */
    public function includeInvoice(LabOrder $lab_order)
    {
        if ($invoice = $lab_order->invoice) {
            return $this->item($invoice, new InvoiceTransformer(), 'invoices');
        }
    }

    /**
     * @param LabOrder $lab_order
     * @return mixed
     */
    public function includeDiscountCode(LabOrder $lab_order)
    {
        if ($discount_code = $lab_order->discountCode) {
            return $this->item($discount_code, new DiscountCodeTransformer(), 'discount_codes');
        }
    }
}
