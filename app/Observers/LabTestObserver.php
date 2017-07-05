<?php

namespace App\Observers;

use App\Models\LabTest;
use Carbon;

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
        if ($labTest->isDirty('status_id') && LabTest::COMPLETE_STATUS_ID == $labTest->status_id) {
            $labTest->completed_at = Carbon::now();
        }
    }

    /**
     * Listen to the LabTest updated event.
     *
     * @param  LabTest $labTest
     * @return void
     */
    public function updated(LabTest $labTest)
    {
        if (LabTest::COMPLETE_STATUS_ID == $labTest->status_id && $labTest->labOrder->labTests->every->isComplete()) {
            $labTest->labOrder->markAsComplete();
        } elseif (LabTest::CANCELED_STATUS_ID == $labTest->status_id && $labTest->labOrder->labTests->every->isCanceled()) {
            $labTest->labOrder->markAsCanceled();
        }
    }
}
