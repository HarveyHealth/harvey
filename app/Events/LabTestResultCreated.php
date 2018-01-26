<?php

namespace App\Events;

use App\Models\LabTestResult;
use Illuminate\Foundation\Events\Dispatchable;

class LabTestResultCreated
{
    use Dispatchable;

    public $lab_test_result;

    public function __construct(LabTestResult $lab_test_result)
    {
        $this->lab_test_result = $lab_test_result;
    }
}
