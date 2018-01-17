<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\LabOrder;

class LabOrderRecommended
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
