<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SKU;

class InvoiceItem extends Model
{
    public function sku()
    {
    	return $this->belongsTo(SKU::class);
    }
}
