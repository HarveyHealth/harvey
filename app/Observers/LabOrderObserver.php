<?php

namespace App\Observers;

use App\Events\{LabOrderConfirmed, LabOrderShipped};
use App\Models\LabOrder;

class LabOrderObserver
{
    /**
     * Listen to the LabOrder saved event.
     *
     * @param  LabOrder $lab_order
     * @return void
     */
    public function saving(LabOrder $lab_order)
    {
        $lab_order->setStatus();

        if ($lab_order->isDirty('status_id')) {
            switch ($lab_order->status_id) {
                case LabOrder::CONFIRMED_STATUS_ID:
                    event(new LabOrderConfirmed($lab_order));
                    break;

                case LabOrder::SHIPPED_STATUS_ID:
                    event(new LabOrderShipped($lab_order));
                    break;

                default:
                    break;
            }
        }
    }
}
