<?php

namespace App\Observers;

use App\Models\LabTest;

class LabTestObserver
{
    /**
     * Listen to the LabTest updated event.
     *
     * @param  LabTest $labTest
     * @return void
     */
    public function updated(LabTest $labTest)
    {
        if (LabTest::COMPLETE_STATUS_ID == $labTest->status_id && $labTest->labOrder->areLabTestsComplete()) {
            $labTest->labOrder->markAsComplete();
        }
    }
}
