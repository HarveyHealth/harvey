<?php

namespace App\Listeners;

use App\Events\AppointmentScheduled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Postmark\PostmarkClient;

class SendPatientAppointmentEmail implements ShouldQueue
{
    public function handle(AppointmentScheduled $event)
    {
        if (app()->environment(['local', 'testing'])) {
            return;
        }
    
        try {
            $template_model = [
                "practitioner_name" => $event->appointment->practitioner->user->fullName(),
                "appointment_date" => $event->appointment->patientAppointmentAtDate()->format('l F j'),
                "appointment_time" => $event->appointment->patientAppointmentAtDate()->format('h:i A'),
                "harvey_id" => $event->appointment->patient->user->id,
                "phone_number" => $event->appointment->patient->user->phone,
                "patient_name" => $event->appointment->patient->user->first_name,
                "patient_phone" => $event->appointment->patient->user->phone
            ];
        
            $client = new PostmarkClient(config('services.postmark.token'));
        
            $client->sendEmailWithTemplate(
                config('services.postmark.signature'),
                $event->appointment->patient->user->email,
                1492142, // Postmark Template ID for welcome email
                $template_model
            );
        } catch (\Exception $exception) {
            \Log::error("Unable to send email to {$event->appointment->patient->user->email}.", [$exception->getMessage()]);
        }
    }
}
