<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{DiscountCode, InvoiceItem, Patient};
use App\Http\Traits\IsNot;
use App\Http\Traits\BelongsToPatient;

class Invoice extends Model
{
    use IsNot, BelongsToPatient;

    const PAID_STATUS = 'paid';
    const PENDING_STATUS = 'pending';
    const CANCELLED_STATUS = 'cancelled';

    protected $dates = [
        'created_at',
        'deleted_at',
        'paid_on',
    ];

    public static function newInvoiceWithData($invoice_data)
    {
        $invoice = new Invoice;
        $discount_code = DiscountCode::find($invoice_data['discount_code_id']);

        if ($discount_code) {
            $invoice->discount_code_id = $discount_code->id;
        }

        $invoice->description = $invoice_data['description'];
        $invoice->patient_id = $invoice_data['patient_id'];

        $invoice->save();

        foreach ($invoice_data['invoice_items'] as $item) {
            $invoice_item = new InvoiceItem;
            $invoice_item->description = $item['description'];
            $invoice_item->item_class = $item['item_class'] ?? null;
            $invoice_item->item_id = $item['item_id'] ?? null;
            $invoice_item->sku_id = $item['sku_id'] ?? null;
            $invoice_item->amount = $item['amount'];
            $invoice_item->invoice_id = $invoice->id;
            $invoice_item->save();
        }

        $invoice->calculateTotals();

        return $invoice;
    }

    public function isOutstanding()
    {
        return ($this->status == self::PENDING_STATUS);
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
        return $query->where('status', self::PENDING_STATUS);
    }

    public function scopeOutstanding($query)
    {
        return $query->where('status', self::PENDING_STATUS);
    }

    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function calculateTotals($reset = false)
    {
        if (empty($this->amount) || $reset) {
            $items = $this->items;

            $subtotal = 0;

            foreach ($items as $item) {
                $subtotal += $item->sku->price;
            }

            $this->subtotal = $subtotal;

            $discount_code = $this->discountCode;

            if ($discount_code) {
                $this->discount = $discount_code->discountForSubtotal($this->subtotal);
            }

            $this->amount = $this->subtotal + $this->discount;

            if ($this->amount < 0) {
                $this->amount = 0;
            }

            $this->save();
        }
    }
}
