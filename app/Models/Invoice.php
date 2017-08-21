<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DiscountCode;
use App\Models\InvoiceItem;

class Invoice extends Model
{
	const PAID_STATUS = 'paid';
	const PENDING_STATUS = 'pending';
	const CANCELLED_STATUS = 'cancelled';

	public static function newInvoiceWithData($invoice_data)
	{
		$invoice = new Invoice;
		$discount_code = DiscountCode::withValidCode($invoice_data['discount_code']);

        if ($discount_code)
        	$invoice->discount_code_id = $discount_code->id;

        foreach ($invoice_data['invoice_items'] as $item) {

        	$invoice_item = new InvoiceItem;
        }

        return $invoice;
	}

	public function scopeForPatient($query, $patient_id)
	{
		return $query->where('patient_id', $patient_id);
	}

	public function scopePaid($query)
	{
		return $query->where('status', self::PAID_STATUS);
	}

	public function scopePending($query)
	{
		return $query->where('status',self::PENDING_STATUS);
	}

    public function discountCode()
    {
    	return $this->hasOne(DiscountCode::class);
    }

    public function calculateTotals($reset = false)
    {
    	if (empty($this->amount) || $reset) {

    		$items = $this->invoiceItems;

    		$subtotal = 0;

    		foreach ($items as $item) {
    			$subtotal += $item->sku->price;
    		}

    		$this->subtotal = $subtotal;

    		$discount_code = $this->discountCode;

    		if ($discount_code) {
    			$this->discount = $discount_code->discountForSubtotal($this->subtotal);
    		}

    		$this->amount = $this->subtotal - $this->discount;

    		if ($this->amount < 0)
    			$this->amount = 0;

    		$this->save();
    	}
    }
}
