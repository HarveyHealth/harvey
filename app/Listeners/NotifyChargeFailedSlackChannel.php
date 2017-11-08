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
        $message = "_Invoice_ #{$event->invoice->id} for *{$event->invoice->patient->user->truncated_name}*.";

        if (!empty($event->transaction)) {
            $message .= " _Transaction_ #{$event->transaction->id}.";
        }

        if (!empty($event->message)) {
            $message .= " _Error_: '{$event->message}'.";
        }

        ops_warning('Charge Failed', $message, 'operations');

        return true;
    }
}
