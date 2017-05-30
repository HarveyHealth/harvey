<?php

namespace App\Listeners;

use App\Events\AppointmentScheduled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendTransactionalEmail;

class SendPatientAppointmentEmail implements ShouldQueue
{
    public function handle(AppointmentScheduled $event)
    {
        $template_model = [
            'practitioner_name' => $event->appointment->practitioner->user->fullName(),
            'appointment_date' => $event->appointment->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $event->appointment->patientAppointmentAtDate()->format('h:i A'),
            'harvey_id' => $event->appointment->patient->user->id,
            'phone_number' => $event->appointment->patient->user->phone,
            'patient_name' => $event->appointment->patient->user->first_name,
            'patient_phone' => $event->appointment->patient->user->phone
        ];

        dispatch(new SendTransactionalEmail(
                        $event->appointment->patient->user->email,
                        'patient.appointment.new',
                        $template_model)
                );
    }
}
