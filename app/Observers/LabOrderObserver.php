<?php

namespace App\Observers;

use App\Events\{LabOrderConfirmed, LabOrderShipped};
use App\Models\{LabOrder, LabTest};

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
        if ($lab_order->labTests->isEmpty()) {
            return true;
        }

        if (1 == $lab_order->labTests->pluck('status_id')->unique()->count()) {
            $lab_order->status_id = $lab_order->labTests->first()->id;
        } else {
            $lab_order->status_id = $lab_order->labTests->pluck('status_id')->diff([LabTest::CANCELED_STATUS_ID])->min();
        }

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
