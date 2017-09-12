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
        $labOrder = $event->lab_order;
        $user = $labOrder->patient->user;

        if ($labOrder->invoice_id && $labOrder->invoice->isNotOutstanding()) {
            return false;
        }

        $invoice = Cashier::getOrCreateInvoice($labOrder);

        if (!$user->hasACard()) {
            ops_warning('ChargePatientForLabOrder warning!' , "User ID #{$user->id} doesn't have a credit card associated. Can't charge Invoice ID #{$invoice->id} [LabOrder ID #{$labOrder->id}].", 'operations');
            return false;
        }

        $job = (new ChargePatientForInvoice($invoice));
        dispatch($job);
    }
}
