<?php

namespace App\Observers;

use App\Models\LabTest;
use App\Events\LabTestProcessing;
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

                case LabTest::PROCESSING_STATUS_ID:
                    event(new \App\Events\LabTestProcessing($lab_test));
                    break;

                default:
                    break;
            }
        }
    }

    /**
     * Listen to the LabTest updated event.
     *
     * @param  LabTest $lab_test
     * @return void
     */
    public function updated(LabTest $lab_test)
    {
        $labOrderSetStatusTriggers = collect([
            LabTest::RECEIVED_STATUS_ID,
            LabTest::COMPLETE_STATUS_ID,
            LabTest::MAILED_STATUS_ID,
            LabTest::PROCESSING_STATUS_ID,
        ]);

        if ($labOrderSetStatusTriggers->contains($lab_test->status_id)) {
            $lab_test->labOrder->setStatus()->save();
        }
    }
}
