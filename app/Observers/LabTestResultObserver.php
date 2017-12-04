<?php

namespace App\Observers;

use App\Models\LabTestResult;
use App\Events\LabTestResultCreated;

class LabTestResultObserver
{
    /**
     * Listen to the LabTestResult created event.
     *
     * @param  LabTestResult $lab_test_result
     * @return void
     */
    public function created(LabTestResult $lab_test_result)
    {
        event(new LabTestResultCreated($lab_test_result));
    }
}
