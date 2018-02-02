<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\{Invoice, InvoiceItem};
use App\Transformers\V1\{InvoiceTransformer, SKUTransformer};

class InvoiceItemTransformer extends HarveyTransformer
{

    protected $availableIncludes = ['invoice', 'sku'];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(InvoiceItem $invoice_item)
    {
        return [
            'id' => cast_to_string($invoice_item->id),
            'invoice_id' => cast_to_string($invoice_item->invoice_id),
            'item_id' => cast_to_string($invoice_item->item_id),
            'item_class' => cast_to_string($invoice_item->item_class),
            'amount' => cast_to_string($invoice_item->amount),
            'description' => cast_to_string($invoice_item->description),
            'sku_id' => cast_to_string($invoice_item->sku_id),
        ];
    }

    /**
     * @param InvoiceItem $invoice_item
     * @return mixed
     */
    public function includeInvoice(InvoiceItem $invoice_item)
    {
        $invoice = $invoice_item->invoice;
        return $this->item($invoice, new InvoiceTransformer)->setResourceKey('invoice');
    }

    public function includeSKU(InvoiceItem $invoice_item)
    {
        $sku = $invoice_item->sku;
        return $this->item($sku, new SKUTransformer)->setResourceKey('sku');
    }
}
