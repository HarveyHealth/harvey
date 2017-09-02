<?php

namespace App\Listeners;

use App\Events\AppointmentComplete;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\Cashier;
use App\Jobs\ChargePatientForInvoice;
use Carbon\Carbon;

class ChargePatientForCompletedAppointment implements ShouldQueue
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
     * @param  AppointmentComplete  $event
     * @return void
     */
    public function handle(AppointmentComplete $event)
    {
        $appointment = $event->appointment;

        // make sure not to charge them again
        if ($appointment->invoice_id && $appointment->invoice->isNotOutstanding()) {
            return false;
        }

        // generate an invoice
        $invoice = Cashier::getOrCreateInvoice($appointment);

        // queue up the charge
        // delated 15 minutes to avoid weird social issues
        $job = (new ChargePatientForInvoice($invoice))->delay(Carbon::now()->addMinutes(15));
        dispatch($job);
    }
}
