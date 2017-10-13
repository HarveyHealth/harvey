<?php

namespace App\Events;

use App\Models\LabTest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LabTestProcessing
{
    use Dispatchable, SerializesModels;

    public $lab_test;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LabTest $lab_test)
    {
        $this->lab_test = $lab_test;
    }
}
