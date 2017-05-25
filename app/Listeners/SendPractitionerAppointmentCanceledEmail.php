<?php

namespace App\Listeners;

use App\Events\AppointmentCanceled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendTransactionalEmail;

class SendPractitionerAppointmentCanceledEmail implements ShouldQueue
{
    public function handle(AppointmentCanceled $event)
    {
        $appointment = $event->appointment;
        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;

        $template_model = [
            'patient_name' => $patient->user->fullName(),
            'appointment_date' => $appointment->practitionerAppointmentAtDate()->format('l F j'),
            'appointment_time' => $appointment->practitionerAppointmentAtDate()->format('h:i A'),
            'appointment_time_zone' => $appointment->practitionerAppointmentAtDate()->format('T'),
            'reschedule_url' => config('app.url') . '/reschedule',
        ];

        dispatch(new SendTransactionalEmail(
                        $practitioner->user->email,
                        'practitioner.appointment.canceled',
                        $template_model)
                );
    }
}
