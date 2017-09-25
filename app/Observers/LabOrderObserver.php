<?php

namespace App\Observers;

use App\Events\LabOrderApproved;
use App\Models\LabOrder;

class LabOrderObserver
{
    /**
     * Listen to the LabOrder updating event.
     *
     * @param  LabOrder $labOrder
     * @return void
     */
    public function updating(LabOrder $labOrder)
    {
        if ($labOrder->isDirty('status_id')) {
            switch ($labOrder->status_id) {
                case LabOrder::CONFIRMED_STATUS_ID:
                    event(new LabOrderApproved($labOrder));
                    break;

                case LabOrder::SHIPPED_STATUS_ID:
                    event(new LabOrderShipped($labOrder));
                    break;

                default:
                    break;
            }
        }
    }
}
