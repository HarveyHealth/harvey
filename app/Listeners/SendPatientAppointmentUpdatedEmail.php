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
                'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
                'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
                'appointment_time_zone' => $appointment->patientAppointmentAtDate()->format('T'),
                'harvey_id' => $patient->user->id,
                'patient_name' => $patient->user->full_name,
                'patient_state' => $patient->user->state,
                'practitioner_name' => $practitioner->user->full_name,
                'practitioner_state' => $practitioner->user->state,
                'reschedule_url' => config('app.url') . '/dashboard#/appointments',
            ]);

        dispatch($transactionalEmailJob);
    }
}
