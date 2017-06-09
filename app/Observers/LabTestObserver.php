<?php

namespace App\Observers;

use App\Models\LabTest;

class LabTestObserver
{
    /**
     * Listen to the LabTest updating event.
     *
     * @param  LabTest $labTest
     * @return void
     */
    public function updating(LabTest $labTest)
    {
        if ($labTest->isDirty('status_id')
        && LabTest::COMPLETE_STATUS_ID == $labTest->status_id
        && $labTest->labOrder->areLabTestsCompleted()) {
                $labTest->labOrder->markAsComplete();
        }
    }
}
