<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\Invoice;

class InvoiceTransformer extends HarveyTransformer
{
    protected $availableIncludes = [
        'appointment',
        'discount_code',
        'items',
        'lab_order',
        'patient',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Invoice $invoice)
    {
        return [
            'id' => cast_to_string($invoice->id),
            'patient_id' => cast_to_string($invoice->patient_id),
            'appointment_id' => cast_to_string($invoice->appointment->id ?? null),
            'lab_order_id' => cast_to_string($invoice->labOrder->id ?? null),
            'amount' => cast_to_string($invoice->amount),
            'card_brand' => $invoice->card_brand,
            'card_last_four' => cast_to_string($invoice->card_last_four),
            'description' => $invoice->description,
            'discount' => cast_to_string($invoice->discount),
            'paid_on' => $invoice->paid_on,
            'status' => $invoice->status,
            'subtotal' => cast_to_string($invoice->subtotal),
        ];
    }

    public function includePatient(Invoice $invoice)
    {
        $transformer = new PatientTransformer();

        if (in_array('user', $this->getCurrentScope()->getManager()->getRequestedIncludes())) {
            $transformer->setDefaultIncludes(['user']);
        }

        return $this->item($invoice->patient, $transformer, 'patient');
    }

    public function includeItems(Invoice $invoice)
    {
        return $this->collection($invoice->items, new InvoiceItemTransformer(), 'invoice_item');
    }

    public function includeDiscountCode(Invoice $invoice)
    {
        if ($discount_code = $invoice->discountCode) {
            return $this->item($discount_code, new DiscountCodeTransformer(), 'discount_code');
        }
    }

    public function includeAppointment(Invoice $invoice)
    {
        if ($appointment = $invoice->appointment) {
            return $this->item($appointment, new AppointmentTransformer(), 'appointment');
        }
    }

    public function includeLabOrder(Invoice $invoice)
    {
        if ($labOrder = $invoice->labOrder) {
            return $this->item($labOrder, new LabOrderTransformer(), 'lab_order');
        }
    }
}
