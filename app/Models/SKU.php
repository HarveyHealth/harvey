<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SKU extends Model
{
    protected $table = 'skus';

    public function labTestInformation()
    {
        return $this->hasOne(LabTestInformation::class, 'sku_id', 'id');
    }
}
