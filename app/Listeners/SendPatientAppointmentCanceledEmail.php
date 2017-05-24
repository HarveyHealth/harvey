<?php

namespace App\Listeners;

use App\Events\AppointmentCanceled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendTransactionalEmail;

class SendPatientAppointmentCanceledEmail implements ShouldQueue
{
    public function handle(AppointmentCanceled $event)
    {
        $appointment = $event->appointment;
        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;

        $template_model = [
            'practitioner_name' => $practitioner->user->fullName(),
            'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->patientAppointmentAtDate()->format('T'),
            'reschedule_url' => config('app.url') . '/reschedule',
        ];

        dispatch(new SendTransactionalEmail(
                        $patient->user->email,
                        'practitioner.appointment.canceled',
                        $template_model)
                );
    }
}
