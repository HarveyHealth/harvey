<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\LabOrder;

class LabOrderTransformer extends HarveyTransformer
{
    protected $availableIncludes = ['patient', 'practitioner', 'lab_tests', 'invoice', 'discount_code'];

    /**
     * @param LabOrder $lab_order
     * @return array
     */
    public function transform(LabOrder $lab_order)
    {
        return [
            'id' => cast_to_string($lab_order->id),
            'address_1' => $lab_order->address_1,
            'address_2' => $lab_order->address_2,
            'city' => $lab_order->city,
            'completed_at' => $lab_order->completed_at,
            'created_at' => $lab_order->created_at,
            'discount_code_id' => cast_to_string($lab_order->discount_code_id),
            'patient_id' => cast_to_string($lab_order->patient_id),
            'practitioner_id' => cast_to_string($lab_order->practitioner_id),
            'shipment_code' => cast_to_string($lab_order->shipment_code),
            'shipment_label_url' => $lab_order->shipment_label_url,
            'shippo_id' => $lab_order->shippo_id,
            'state' => $lab_order->state,
            'status' => $lab_order->status,
            'zip' => cast_to_string($lab_order->zip),
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

        return $this->item($lab_order->patient, $transformer, 'patient');
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

        return $this->item($lab_order->practitioner, $transformer, 'practitioner');
    }

    /**
     * @param LabOrder $lab_order
     * @return mixed
     */
    public function includeLabTests(LabOrder $lab_order)
    {
        return $this->collection($lab_order->labTests, new LabTestTransformer(), 'lab_test');
    }

    /**
     * @param LabOrder $lab_order
     * @return mixed
     */
    public function includeInvoice(LabOrder $lab_order)
    {
        if ($invoice = $lab_order->invoice) {
            return $this->item($invoice, new InvoiceTransformer(), 'invoice');
        }
    }

    /**
     * @param LabOrder $lab_order
     * @return mixed
     */
    public function includeDiscountCode(LabOrder $lab_order)
    {
        if ($discount_code = $lab_order->discountCode) {
            return $this->item($discount_code, new DiscountCodeTransformer(), 'discount_code');
        }
    }
}
