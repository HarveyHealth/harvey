<?php

namespace App\Listeners;

use App\Events\CreditCardUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Invoice;

class PayOutstandingInvoicesForPatient
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
     * @param  CreditCardUpdated  $event
     * @return void
     */
    public function handle(CreditCardUpdated $event)
    {
        $user = $event->user;

        // only interested in patients in this one
        if (!$user->isPatient())
            return;

        $invoices = Invoice::forPatient($user->patient->id)->outstanding()->get();

        foreach ($invoices as $invoice) {

            // queue up the charge
            $job = (new ChargePatientForInvoice($invoice));
            dispatch($job);
        }
    }
}
