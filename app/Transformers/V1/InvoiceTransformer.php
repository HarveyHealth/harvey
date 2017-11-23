<?php

namespace App\Transformers\V1;

use App\Models\Invoice;
use League\Fractal\TransformerAbstract;

class InvoiceTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Invoice $invoice)
    {
        return [
            'id' => cast_to_string($invoice->id),
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
}
