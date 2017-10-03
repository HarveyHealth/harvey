<?php

namespace App\Listeners;

use App\Events\AppointmentComplete;
use App\Models\SKU;
use App\Lib\{Cashier, TransactionalEmail};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPatientAppointmentCompleteEmail implements ShouldQueue
{
    public function handle(AppointmentComplete $event)
    {
        $invoice = Cashier::getOrCreateInvoice($event->appointment);

        $transactionalEmailJob = TransactionalEmail::createJob()
        ->setTo($event->appointment->patient->user->email)
        ->setTemplate('patient.appointment.complete')
        ->setTemplateModel([
            'doctor' => $event->appointment->practitioner->user->full_name,
            'duration' => $event->appointment->duration_in_minutes,
            'rate' => SKU::findBySlugOrFail('60-minute-consultation')->price,
            'total' => $invoice->amount,
        ]);

        dispatch($transactionalEmailJob);
    }
}
