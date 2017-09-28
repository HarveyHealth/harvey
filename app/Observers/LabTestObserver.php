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
        $status_id = $lab_test->status_id;

        $same_status = function ($value, $key) use ($status_id) { return $value->status_id == $status_id; };

        if ($lab_test->labOrder->labTests->every($same_status)) {
            $method_name = 'markAs' . ucfirst($lab_test->status);
            $lab_test->labOrder->$method_name();
        } else {
            $weakest_status_id = $lab_test->labOrder->labTests->pluck('status_id')->diff([LabTest::CANCELED_STATUS_ID])->min();
            $lab_test->labOrder->status_id = $weakest_status_id;
            $lab_test->labOrder->save();
        }
    }
}
