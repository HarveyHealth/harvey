<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTestInformation extends Model
{
    use SoftDeletes;

    protected $table = 'lab_tests_information';

    public function sku()
    {
        return $this->belongsTo(SKU::class);
    }
}
