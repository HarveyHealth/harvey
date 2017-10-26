<?php

namespace App\Listeners;

use App\Events\AppointmentUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Lib\TransactionalEmail;

class SendPractitionerAppointmentUpdatedEmail implements ShouldQueue
{
    public function handle(AppointmentUpdated $event)
    {
        $appointment = $event->appointment;
        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;

        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($practitioner->user->email)
            ->setTemplate('practitioner.appointment.updated')
            ->setTemplateModel([
                'appointment_date' => $appointment->practitionerAppointmentAtDate()->format('l F j'),
                'appointment_time' => $appointment->practitionerAppointmentAtDate()->format('h:i A'),
                'appointment_time_zone' => $appointment->practitionerAppointmentAtDate()->format('T'),
                'patient_name' => $patient->user->full_name,
                'patient_phone' => $appointment->patient->user->phone,
                'patient_state' => $appointment->patient->user->state,
                'practitioner_name' => $appointment->practitioner->user->full_name,
                'practitioner_state' => $appointment->practitioner->user->state,
                'reschedule_url' => config('app.url') . '/dashboard#/appointments',
            ]);

        dispatch($transactionalEmailJob);
    }
}
