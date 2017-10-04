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
     * @param  LabTest $lab_test
     * @return void
     */
    public function updating(LabTest $lab_test)
    {
        if ($lab_test->isDirty('status_id')) {
            switch ($lab_test->status_id) {
                case LabTest::COMPLETE_STATUS_ID:
                    $lab_test->completed_at = Carbon::now();
                    break;

                case LabTest::RECEIVED_STATUS_ID:
                    event(new LabTestReceived($lab_test));
                    $lab_test->labOrder->setStatus();
                    $lab_test->labOrder->save();
                    break;

                case LabTest::COMPLETE_STATUS_ID:
                    $lab_test->labOrder->setStatus();
                    $lab_test->labOrder->save();
                    break;

                case LabTest::MAILED_STATUS_ID:
                    $lab_test->labOrder->setStatus();
                    $lab_test->labOrder->save();
                    break;

                case LabTest::PROCESSING_STATUS_ID:
                    $lab_test->labOrder->setStatus();
                    $lab_test->labOrder->save();
                    break;

                default:
                    break;
            }
        }
    }
}
