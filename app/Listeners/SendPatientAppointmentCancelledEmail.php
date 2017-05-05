<?php

namespace App\Listeners;

use App\Events\AppointmentCancelled;
use App\Http\Traits\PostmarkExceptionHandler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;

class SendPatientAppointmentCancelledEmail implements ShouldQueue
{
    public function handle(AppointmentCancelled $event)
    {
        $appointment = $event->appointment;
        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;

        try {
            $templateData = [
                'practitioner_name' => $practitioner->user->fullName(),
                'appointment_date' => $appointment->patientAppointmentAtDate()->format('l F j'),
                'appointment_time' => $appointment->patientAppointmentAtDate()->format('h:i A'),
                'appointment_time_zone' => $appointment->patientAppointmentAtDate()->format('T'),
                'reschedule_url' => config('app.url') . '/reschedule',
            ];

            $client = new PostmarkClient(config('services.postmark.token'));

            $client->sendEmailWithTemplate(
                config('services.postmark.signature'),
                $patient->user->email,
                config('services.postmark.templates.practitioner.appointment.cancelled'),
                $templateData
            );
        } catch (PostmarkException $exception) {
            self::handlePostmarkException($exception);
        }
    }
}
