<?php

namespace App\Listeners;

use App\Events\AppointmentCanceled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Lib\TransactionalEmail;

class SendPatientAppointmentCanceledEmail implements ShouldQueue
{
    public function handle(AppointmentCanceled $event)
    {
        $appointment = $event->appointment;
        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;

        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($patient->user->email)
            ->setTemplate('patient.appointment.canceled')
            ->setTemplateModel([
                'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
                'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
                'appointment_time_zone' => $appointment->patientAppointmentAtDate()->format('T'),
                'patient_name' => $patient->user->first_name,
                'patient_state' => $patient->user->state,
                'practitioner_name' => $practitioner->user->full_name,
                'practitioner_state' => $practitioner->user->state,
                'reschedule_url' => config('app.url') . '/dashboard#/appointments',
            ]);

        dispatch($transactionalEmailJob);
    }
}
