<?php

namespace App\Listeners;

use App\Events\AppointmentComplete;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\Cashier;
use App\Jobs\ChargePatientForInvoice;
use Carbon\Carbon;

class ChargePatientForCompleteAppointment implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  AppointmentComplete  $event
     * @return void
     */
    public function handle(AppointmentComplete $event)
    {
        $appointment = $event->appointment;
        $user = $appointment->patient->user;

        if ($appointment->invoice_id && $appointment->invoice->isNotOutstanding()) {
            return false;
        }

        $invoice = Cashier::getOrCreateInvoice($appointment);

        if (!$user->hasACard()) {
            ops_warning('ChargePatientForCompleteAppointment warning!' , "User ID #{$user->id} doesn't have a credit card associated. Can't charge Invoice ID #{$invoice->id} [Appointment ID #{$appointment->id}].", 'operations');
            return false;
        }

        dispatch((new ChargePatientForInvoice($invoice))->delay(Carbon::now()->addMinutes(30)));
    }
}
