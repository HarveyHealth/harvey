<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SKU extends Model
{
    protected $table = 'skus';

    public function scopeItemType($query, $type)
    {
    	return $query->where('item_type', $type);
    }

    public function labTestInformation()
    {
        return $this->hasOne(LabTestInformation::class, 'sku_id', 'id');
    }
}
