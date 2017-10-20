<?php

namespace App\Listeners;

use App\Events\ChargeSucceeded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyChargeSucceededSlackChannel implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  ChargeSucceeded  $event
     * @return void
     */
    public function handle(ChargeSucceeded $event)
    {
        ops_success('Charge Succeeded', "Invoice #{$event->invoice->id} for {$event->invoice->patient->user->truncatedName()}.", 'operations');

        return true;
    }
}
