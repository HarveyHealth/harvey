<?php

namespace App\Listeners;

use App\Events\ChargeSucceeded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyOfSuccessfulCharge
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
     * @param  ChargeSucceeded  $event
     * @return void
     */
    public function handle(ChargeSucceeded $event)
    {
        $invoice = $event->invoice;
        $patient_user = $invoice->patient->user;

        // send a message to operations
        $message = 'Invoice #' . $invoice->id . ' for ' . $patient_user->truncatedName();
        ops_success('Charge Succeeded', $message, 'operations');

        // send receipt emails here
    }
}
