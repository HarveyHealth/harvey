<?php

namespace App\Observers;

use App\Models\LabTest;
use App\Events\LabTestReceived;
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
        if ($labTest->isDirty('status_id')) {
            switch ($labTest->status_id) {
                case LabTest::COMPLETE_STATUS_ID:
                    $labTest->completed_at = Carbon::now();
                    break;

                case LabTest::RECEIVED_STATUS_ID:
                    event(new LabTestReceived($labTest));
                    break;

                default:
                    break;
            }
        }
    }
}
