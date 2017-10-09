<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use App\Models\LabOrder;

class LabOrderConfirmed
{
    use Dispatchable, SerializesModels;

    public $lab_order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LabOrder $lab_order)
    {
        $this->lab_order = $lab_order;
    }
}
