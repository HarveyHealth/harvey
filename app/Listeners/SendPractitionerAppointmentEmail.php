<?php

namespace App\Listeners;

use App\Events\AppointmentScheduled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Postmark\PostmarkClient;

class SendPractitionerAppointmentEmail implements ShouldQueue
{
    public function handle(AppointmentScheduled $event)
    {
//        if (app()->environment(['local', 'testing'])) {
//            return;
//        }
        
        try {
            $template_model = [
                "appointment_date" => $event->appointment->practitionerAppointmentAtDate()->format('l F j'),
                "appointment_time" => $event->appointment->practitionerAppointmentAtDate()->format('h:i A'),
                "patient_name" => $event->appointment->patient->user->fullName(),
                "patient_phone" => $event->appointment->patient->user->phone,
                "practitioner_name" => $event->appointment->practitioner->user->first_name
            ];
            
            $client = new PostmarkClient(config('services.postmark.token'));
            
            $client->sendEmailWithTemplate(
                config('services.postmark.signature'),
                $event->appointment->practitioner->user->email,
                1529541, // Postmark Template ID for welcome email
                $template_model
            );
        } catch (\Exception $exception) {
            \Log::error("Unable to send email to {$event->appointment->practitioner->user->email}.", [$exception->getMessage()]);
        }
    }
}
