<?php

namespace App\Listeners;

use App\Events\LabOrderConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\Cashier;
use App\Jobs\ChargePatientForInvoice;

class ChargePatientForLabOrder implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  LabOrderConfirmed  $event
     * @return void
     */
    public function handle(LabOrderConfirmed $event)
    {
        if ($event->lab_order->invoice_id && $event->lab_order->invoice->isNotOutstanding()) {
            return false;
        }

        $invoice = Cashier::getOrCreateInvoice($event->lab_order);

        if (!$event->lab_order->patient->user->hasACard()) {
            ops_warning('ChargePatientForLabOrder warning!' , "User ID #{$event->lab_order->patient->user->id} doesn't have a credit card associated. Can't charge Invoice ID #{$invoice->id} [LabOrder ID #{$event->lab_order->id}].", 'operations');
            return false;
        }

        dispatch(new ChargePatientForInvoice($invoice));
    }
}
