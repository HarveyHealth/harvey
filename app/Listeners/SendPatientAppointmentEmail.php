<?php

namespace App\Listeners;

use App\Events\AppointmentScheduled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Lib\TransactionalEmail;

class SendPatientAppointmentEmail implements ShouldQueue
{
    public function handle(AppointmentScheduled $event)
    {
        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($event->appointment->patient->user->email)
            ->setTemplate('patient.appointment.new')
            ->setTemplateModel([
                'appointment_date' => $event->appointment->patientAppointmentAtDate()->format('l F j'),
                'appointment_time' => $event->appointment->patientAppointmentAtDate()->format('h:i A'),
                'appointment_time_zone' => $event->appointment->patientAppointmentAtDate()->format('T'),
                'harvey_id' => $event->appointment->patient->user->id,
                'intake_link' => $event->appointment->patient->intake_link,
                'patient_name' => $event->appointment->patient->user->first_name,
                'patient_phone' => $event->appointment->patient->user->phone,
                'patient_state' => $event->appointment->patient->user->state,
                'phone_number' => $event->appointment->patient->user->phone,
                'practitioner_name' => $event->appointment->practitioner->user->full_name,
                'practitioner_state' => $event->appointment->practitioner->user->state,
            ]);

        dispatch($transactionalEmailJob);
    }
}
