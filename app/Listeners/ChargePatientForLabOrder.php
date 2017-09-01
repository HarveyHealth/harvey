<?php

namespace App\Listeners;

use App\Events\LabOrderApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\Cashier;
use App\Jobs\ChargePatientForInvoice;

class ChargePatientForLabOrder implements ShouldQueue
{
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

        // make sure not to charge them again
        if ($lab_order->invoice_id && $lab_order->invoice->isNotOutstanding()) {
            return false;
        }

        // generate the invoice
        $cashier = new Cashier;
        $invoice = $cashier->generatePatientInvoiceForInvoiceable($lab_order);

        // queue up the charge
        $job = (new ChargePatientForInvoice($invoice));
        dispatch($job);
    }
}
