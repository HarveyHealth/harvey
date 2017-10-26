<?php

namespace App\Models;

use App\Models\LabTest;
use App\Http\Traits\HasKeyColumn;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class LabTestResult extends Model
{
    use HasKeyColumn, SoftDeletes;

    protected $table = 'lab_tests_results';

    protected $guarded = ['id', 'lab_test_id'];

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }
}
