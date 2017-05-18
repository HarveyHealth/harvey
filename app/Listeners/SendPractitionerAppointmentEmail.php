<?php

namespace App\Listeners;

use App\Events\AppointmentScheduled;
use App\Http\Traits\PostmarkExceptionHandler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;

class SendPractitionerAppointmentEmail implements ShouldQueue
{
    use PostmarkExceptionHandler;

    public function handle(AppointmentScheduled $event)
    {
        if (!app()->environment('local', 'testing')) {
            try {
                $template_model = [
                    'appointment_date' => $event->appointment->practitionerAppointmentAtDate()->format('l F j'),
                    'appointment_time' => $event->appointment->practitionerAppointmentAtDate()->format('h:i A'),
                    'patient_name' => $event->appointment->patient->user->fullName(),
                    'patient_phone' => $event->appointment->patient->user->phone,
                    'practitioner_name' => $event->appointment->practitioner->user->first_name,
                ];

                $client = new PostmarkClient(config('services.postmark.token'));

                $client->sendEmailWithTemplate(
                    config('services.postmark.signature'),
                    $event->appointment->practitioner->user->email,
                    config('services.postmark.templates.practitioner.appointment.new'),
                    $template_model
                );
            } catch (PostmarkException $exception) {
                self::handlePostmarkException($exception, $event);
            }
        }
    }
}
