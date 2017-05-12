<?php

namespace App\Listeners;

use App\Events\AppointmentScheduled;
use App\Http\Traits\PostmarkExceptionHandler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;

class SendPatientAppointmentEmail implements ShouldQueue
{
    public function handle(AppointmentScheduled $event)
    {
        if (!app()->environment('local', 'testing')) {
            try {
                $template_model = [
                    'practitioner_name' => $event->appointment->practitioner->user->fullName(),
                    'appointment_date' => $event->appointment->patientAppointmentAtDate()->format('l F j'),
                    'appointment_time' => $event->appointment->patientAppointmentAtDate()->format('h:i A'),
                    'harvey_id' => $event->appointment->patient->user->id,
                    'phone_number' => $event->appointment->patient->user->phone,
                    'patient_name' => $event->appointment->patient->user->first_name,
                    'patient_phone' => $event->appointment->patient->user->phone
                ];

                $client = new PostmarkClient(config('services.postmark.token'));

                $client->sendEmailWithTemplate(
                    config('services.postmark.signature'),
                    $event->appointment->patient->user->email,
                    config('services.postmark.templates.patient.appointment.new'),
                    $template_model
                );
            } catch (PostmarkException $exception) {
                self::handlePostmarkException($exception);
            }
        }
    }
}
