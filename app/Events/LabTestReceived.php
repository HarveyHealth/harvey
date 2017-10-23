<?php

namespace App\Events;

use App\Models\LabTest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LabTestReceived
{
    use Dispatchable, SerializesModels;

    public $labTest;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LabTest $labTest)
    {
        $this->labTest = $labTest;
    }
}
