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
        $status_id = $labTest->status_id;

        $sameStatus = function ($value, $key) use ($status_id) { return $value->status_id == $status_id; };

        if ($labTest->labOrder->labTests->every($sameStatus)) {
            $methodName = 'markAs' . ucfirst($labTest->status);
            $labTest->labOrder->$methodName();
        }
    }
}
