<?php

namespace App\Listeners;

use App\Events\LabOrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateLabOrderWithShipmentLabel implements ShouldQueue
{
    public function handle(LabOrderShipped $event)
    {
        // try catch shippo info
        // $event->labOrder->labTests->map(sum dimensions)
        // call shippo
        // get data & save to db

        \Log::info('Lab Order has shipped ' . $event->labOrder->id);
    }
}
