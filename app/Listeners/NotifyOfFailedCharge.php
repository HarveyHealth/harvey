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

        $message = 'Invoice ' . $invoice-Lid . ' for ' . $patient_user->truncatedName();

        ops_warning('Charge Failed', $message, 'operations');
    }
}
