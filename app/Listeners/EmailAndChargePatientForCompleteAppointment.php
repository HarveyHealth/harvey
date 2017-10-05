<?php

namespace App\Listeners;

use App\Events\AppointmentComplete;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\Cashier;
use App\Jobs\ChargePatientForInvoice;
use Carbon\Carbon;
use App\Lib\TransactionalEmail;
use App\Models\SKU;

class EmailAndChargePatientForCompleteAppointment implements ShouldQueue
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

        $duration = $appointment->duration_in_minutes;
        $slug = $duration . '-minute-consultation';

        $transactionalEmailJob = TransactionalEmail::createJob()
        ->setTo($appointment->patient->user->email)
        ->setTemplate('patient.appointment.complete')
        ->setTemplateModel([
            'doctor' => $appointment->practitioner->user->full_name,
            'duration' => $appointment->duration_in_minutes,
            'rate' => SKU::findBySlugOrFail($slug)->price,
            'total' => $invoice->amount,
        ]);

        dispatch($transactionalEmailJob);
        dispatch((new ChargePatientForInvoice($invoice))->delay(Carbon::now()->addMinutes(2)));
    }
}
