<?php

namespace App\Http\Traits;

use App\Models\Invoice;

trait Invoiceable
{
    public abstract function dataForInvoice();

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }
}
