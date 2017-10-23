<?php

namespace App\Events;

use App\Models\LabOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LabOrderShipped
{
    use Dispatchable, SerializesModels;

    public $labOrder;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LabOrder $labOrder)
    {
        $this->labOrder = $labOrder;
    }
}
