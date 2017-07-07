<?php

namespace App\Listeners;

use App\Events\AppointmentUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Lib\TransactionalEmail;

class SendPatientAppointmentUpdatedEmail implements ShouldQueue
{

    public function handle(AppointmentUpdated $event)
    {
        $appointment = $event->appointment;
        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;

        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($patient->user->email)
            ->setTemplate('patient.appointment.updated')
            ->setTemplateModel([
            'practitioner_name' => $practitioner->user->full_name,
            'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->patientAppointmentAtDate()->format('T'),
            'reschedule_url' => config('app.url') . '/dashboard#/appointments',
            'doctor_state' => $practitioner->doctor_state,
        ]);

        dispatch($transactionalEmailJob);
    }
}
