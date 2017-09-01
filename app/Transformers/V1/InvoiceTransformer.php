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
            'id' => (string) $invoice->id,
            'amount' => (string) $invoice->amount,
            'card_brand' => $invoice->card_brand,
            'card_last_four' => (string) $invoice->card_last_four,
            'description' => $invoice->description,
            'discount' => (string) $invoice->discount,
            'paid_on' => $invoice->paid_on,
            'status' => $invoice->status,
            'subtotal' => (string) $invoice->subtotal,
        ];
    }
}
