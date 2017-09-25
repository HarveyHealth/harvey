<?php

namespace App\Events;

use App\Models\LabOrder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ShipmentInitiated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $labOrder;
    
    public function __construct(LabOrder $labOrder)
    {
        $this->labOrder = $labOrder;
    }
}
