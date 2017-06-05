<?php

namespace App\Listeners;

use App\Events\AppointmentCanceled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendTransactionalEmail;

class SendPatientAppointmentCanceledEmail implements ShouldQueue
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
            ->setTo($patient->user->email)
            ->setTemplate('patient.appointment.canceled')
            ->setTemplateModel([
            'practitioner_name' => $practitioner->user->fullName(),
            'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->patientAppointmentAtDate()->format('T'),
            'reschedule_url' => config('app.url') . '/dashboard#/appointments',
        ]);

        dispatch($this->sendTransactionalEmail);
    }
}
