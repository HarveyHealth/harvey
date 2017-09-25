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
        } else {
            $weakestStatusId = $labTest->labOrder->labTests->pluck('status_id')->diff([LabTest::CANCELED_STATUS_ID])->min();
            $labTest->labOrder->status_id = $weakestStatusId;
            $labTest->labOrder->save();
        }
    }
}
