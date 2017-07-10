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
            'patient_name' => $patient->user->fullName(),
            'appointment_date' => $appointment->practitionerAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->practitionerAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->practitionerAppointmentAtDate()->format('T'),
            'reschedule_url' => config('app.url') . '/dashboard#/appointments',
            'doctor_state' => $practitioner->doctor_state,
        ]);

        dispatch($transactionalEmailJob);
    }
}
