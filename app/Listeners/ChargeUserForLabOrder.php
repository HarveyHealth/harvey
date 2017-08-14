<?php

namespace App\Listeners;

use App\Events\LabOrderApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Stripe\Stripe;

class ChargeUserForLabOrder implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LabOrderApproved  $event
     * @return void
     */
    public function handle(LabOrderApproved $event)
    {
        $lab_order = $event->lab_order;
        $user = $lab_order->patient->user;
    }
}
