<?php

namespace App\Listeners;

use App\Events\ChargeFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyOfFailedCharge
{
    /**
     * Handle the event.
     *
     * @param  ChargeFailed  $event
     * @return void
     */
    public function handle(ChargeFailed $event)
    {
        $invoice = $event->invoice;
        $exception = $event->exception;
        $transaction = $event->transaction;
        $patientUser = $invoice->patient->user;

        $message = "Invoice #{$invoice->id} for '{$patientUser->truncatedName()}'.";

        if (!empty($exception)) {
            $message .= " Error: '{$exception->getMessage()}'.";
        }

        if (!empty($transaction)) {
            $message .= " Transaction #{$transaction->id}.";
        }

        ops_warning('Charge Failed', $message, 'operations');

        // we need to fire of an email to the user, but we need copy
    }
}
