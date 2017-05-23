<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SendTransactionalEmail;

class SendWelcomeEmail implements ShouldQueue
{
    /**
     * @param UserRegistered $event
     */
    public function handle(UserRegistered $event)
    {
        if (!app()->environment(['production','staging'])) {
            \Log::info("User id {$event->user->id} with email {$event->user->email} was registered.");
            return;
        }

        $template_model = [
            'name' => $event->user->fullName(),
            'action_url' => $event->user->emailVerificationURL()
        ];

        dispatch(new SendTransactionalEmail(
                        $event->user->email,
                        'patient.welcome',
                        $template_model)
                );
    }
}
