<?php

namespace App\Listeners;

use App\Events\AppointmentScheduled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Lib\TransactionalEmail;

class SendPractitionerAppointmentEmail implements ShouldQueue
{

    public function handle(AppointmentScheduled $event)
    {
        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($event->appointment->practitioner->user->email)
            ->setTemplate('practitioner.appointment.new')
            ->setTemplateModel([
            'appointment_date' => $event->appointment->practitionerAppointmentAtDate()->format('l F j'),
            'appointment_time' => $event->appointment->practitionerAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $event->appointment->practitionerAppointmentAtDate()->format('T'),
            'patient_name' => $event->appointment->patient->user->fullName(),
            'patient_phone' => $event->appointment->patient->user->phone,
            'practitioner_name' => $event->appointment->practitioner->user->first_name,
            'doctor_state' => $event->appointment->practitioner->doctor_state,
        ]);

        dispatch($transactionalEmailJob);
    }
}
