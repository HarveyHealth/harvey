<?php

namespace App\Listeners;

use App\Events\AppointmentUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendTransactionalEmail;

class SendPatientAppointmentUpdatedEmail implements ShouldQueue
{
    public function handle(AppointmentUpdated $event)
    {
        $appointment = $event->appointment;
        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;

        $template_model = [
            'practitioner_name' => $practitioner->user->fullName(),
            'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->patientAppointmentAtDate()->format('T'),
            'reschedule_url' => config('app.url') . '/dashboard#/appointments',
        ];

        dispatch(new SendTransactionalEmail(
                        $patient->user->email,
                        'practitioner.appointment.updated',
                        $template_model)
                );
    }
}
