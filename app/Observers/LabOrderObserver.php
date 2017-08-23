<?php

namespace App\Observers;

use App\Events\LabOrderApproved;
use App\Models\LabOrder;

class LabOrderObserver
{
    /**
     * Listen to the LabOrder updating event.
     *
     * @param  LabOrder $order
     * @return void
     */
    public function updating(LabOrder $order)
    {
        // if we've changed to confirmed...
        if ($order->isDirty('status_id') && $order->status_id == LabOrder::CONFIRMED_STATUS_ID) {
            event(new LabOrderApproved($order));
        }
    }
}
