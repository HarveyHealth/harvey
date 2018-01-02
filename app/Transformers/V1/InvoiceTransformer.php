<?php

namespace App\Transformers\V1;

use App\Models\Invoice;
use App\Transformers\V1\{InvoiceItemTransformer, PatientTransformer, DiscountCodeTransformer};
use League\Fractal\TransformerAbstract;

class InvoiceTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['patient', 'invoice_items', 'discount_code'];

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

    public function includeInvoiceItems(Invoice $invoice)
    {
        return $this->collection($invoice->items, new InvoiceItemTransformer(), 'invoice_item');
    }

    public function includeDiscountCode(Invoice $invoice)
    {
        if ($discount_code = $invoice->discountCode) {
            return $this->item($discount_code, new DiscountCodeTransformer(), 'discount_code');
        }
    }
}
