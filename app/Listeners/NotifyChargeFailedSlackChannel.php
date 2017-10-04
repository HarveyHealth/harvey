<?php

namespace App\Listeners;

use App\Events\ChargeFailed;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyChargeFailedSlackChannel implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  ChargeFailed  $event
     * @return void
     */
    public function handle(ChargeFailed $event)
    {
        $message = "Invoice #{$event->invoice->id} for {$event->invoice->patient->user->truncatedName()}.";

        if (!empty($event->exception)) {
            $message .= " Error: '{$event->exception->getMessage()}'.";
        }

        if (!empty($event->transaction)) {
            $message .= " Transaction #{$event->transaction->id}.";
        }

        ops_warning('Charge Failed', $message, 'operations');

        return true;
    }
}
