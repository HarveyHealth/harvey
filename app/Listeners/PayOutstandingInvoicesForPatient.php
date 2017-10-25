<?php

namespace App\Listeners;

use App\Events\CreditCardUpdated;
use App\Jobs\ChargePatientForInvoice;
use App\Models\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;

class PayOutstandingInvoicesForPatient implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  CreditCardUpdated  $event
     * @return void
     */
    public function handle(CreditCardUpdated $event)
    {
        if ($event->user->isNotPatient()) {
            return false;
        }

        Invoice::forPatient($event->user->patient->id)->outstanding()->get()->map(function ($invoice) {
            return dispatch(new ChargePatientForInvoice($invoice));
        });

        return true;
    }
}
