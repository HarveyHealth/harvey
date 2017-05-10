<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Postmark\PostmarkClient;

class SendWelcomeEmail implements ShouldQueue
{
    /**
     * @param UserRegistered $event
     */
    public function handle(UserRegistered $event)
    {
        if (app()->environment(['local', 'testing'])) {
            \Log::info(
                "User id {$event->user->id} with email {$event->user->email} was registered."
            );
            return;
        }

        try {
            $template_model = [
                'name' => $event->user->fullName(),
                'action_url' => $event->user->emailVerificationURL()
            ];

            $client = new PostmarkClient(config('services.postmark.token'));

            $client->sendEmailWithTemplate(
                config('services.postmark.signature'),
                $event->user->email,
                config('services.postmark.templates.patient.welcome'),
                $template_model
            );
        } catch (\Exception $exception) {
            \Log::error("Unable to send email to {$event->user->email}.", [$exception->getMessage()]);
        }
    }
}
