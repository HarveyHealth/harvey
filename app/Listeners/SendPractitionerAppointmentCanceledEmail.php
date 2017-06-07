<?php

namespace App\Listeners;

use App\Events\AppointmentCanceled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendTransactionalEmail;

class SendPractitionerAppointmentCanceledEmail implements ShouldQueue
{
    protected $sendTransactionalEmail;

    public function __construct(SendTransactionalEmail $sendTransactionalEmail)
    {
        $this->sendTransactionalEmail = $sendTransactionalEmail;
    }

    public function handle(AppointmentCanceled $event)
    {
        $appointment = $event->appointment;
        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;

        $this->sendTransactionalEmail
            ->setTo($practitioner->user->email)
            ->setTemplate('practitioner.appointment.canceled')
            ->setTemplateModel([
            'patient_name' => $patient->user->fullName(),
            'appointment_date' => $appointment->practitionerAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->practitionerAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->practitionerAppointmentAtDate()->format('T'),
            'reschedule_url' => config('app.url') . '/dashboard#/appointments',
            'doctor_state' => $practitioner->doctor_state,
        ]);

        dispatch($this->sendTransactionalEmail);
    }
}
