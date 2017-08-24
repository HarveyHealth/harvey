<?php

namespace App\Listeners;

use App\Events\ChargeFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyOfFailedCharge
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
     * @param  ChargeFailed  $event
     * @return void
     */
    public function handle(ChargeFailed $event)
    {
        $invoice = $event->invoice;
        $patient_user = $invoice->patient->user;

        // send a message to operations
        $message = 'Invoice #' . $invoice->id . ' for ' . $patient_user->truncatedName();
        ops_warning('Charge Failed', $message, 'operations');

        // we need to fire of an email to the user, but we need copy
    }
}
