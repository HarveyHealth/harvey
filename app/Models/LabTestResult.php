<?php

namespace App\Models;

use App\Models\LabTest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTestResult extends Model
{
    use SoftDeletes;

    protected $table = 'lab_tests_results';

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }
}
