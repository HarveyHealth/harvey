<?php

namespace App\Listeners;

use App\Events\AppointmentCanceled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Lib\TransactionalEmail;

class SendPractitionerAppointmentCanceledEmail implements ShouldQueue
{

    public function handle(AppointmentCanceled $event)
    {
        $appointment = $event->appointment;
        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;

        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($practitioner->user->email)
            ->setTemplate('practitioner.appointment.canceled')
            ->setTemplateModel([
                'appointment_date' => $appointment->practitionerAppointmentAtDate()->format('l F j'),
                'appointment_time' => $appointment->practitionerAppointmentAtDate()->format('h:i A'),
                'appointment_time_zone' => $appointment->practitionerAppointmentAtDate()->format('T'),
                'patient_name' => $patient->user->full_name,
                'patient_state' => $patient->user->state,
                'practitioner_name' => $practitioner->user->first_name,
                'practitioner_state' => $practitioner->user->state,
                'reschedule_url' => config('app.url') . '/dashboard#/appointments',
            ]);

        dispatch($transactionalEmailJob);
    }
}
