<?php

namespace App\Http\Traits;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\DiscountCode;

trait Invoiceable
{
	protected function dataForInvoice()
    {
    	// this is a stub and should be overwritten in the traited class
    	return [];
    }

    public function invoice()
    {
    	return $this->hasOne(Invoice::class);
    }
}
