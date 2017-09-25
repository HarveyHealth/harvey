<?php

namespace App\Listeners;

use App\Events\ShipmentInitiated;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateLabOrderWithShipmentLabel implements ShouldQueue
{
    public function handle(ShipmentInitiated $event)
    {
        // try catch shippo info
        // $event->labOrder->labTests->map(sum dimensions)
        // call shippo
        // get data & save to db
    }
}
