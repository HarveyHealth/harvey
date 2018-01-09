<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{SKU, Invoice};


class InvoiceItem extends Model
{
    public function invoice()
    {
    	return $this->belongsTo(Invoice::class);
    }
    public function sku()
    {
    	return $this->belongsTo(SKU::class);
    }
}
