<?php

namespace App\Listeners;

use App\Events\AppointmentComplete;
use App\Models\SKU;
use App\Lib\{Cashier, TransactionalEmail};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Carbon\Carbon;

class SendPatientAppointmentCompleteEmail implements ShouldQueue
{
    public function handle(AppointmentComplete $event)
    {
        $appointment = $event->appointment;
        $invoice = $appointment->invoice;

        if (!$invoice) {
            ops_error('SendPatientAppointmentCompleteEmail error!', "No invoice exists for Appointment #{$appointment->id}. No email sent.");
            return;
        }

        $duration = $event->appointment->duration_in_minutes;
        $slug = $duration . '-minute-consultation';

        $transactionalEmailJob = TransactionalEmail::createJob()
        ->setTo($event->appointment->patient->user->email)
        ->setTemplate('patient.appointment.complete')
        ->setTemplateModel([
            'doctor' => $event->appointment->practitioner->user->full_name,
            'duration' => $event->appointment->duration_in_minutes,
            'rate' => SKU::findBySlugOrFail($slug)->price,
            'total' => $invoice->amount,
        ]);

        dispatch($transactionalEmailJob);
    }
}
