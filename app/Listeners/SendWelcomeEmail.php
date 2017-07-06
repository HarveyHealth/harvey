<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\TransactionalEmail;

class SendWelcomeEmail implements ShouldQueue
{
    /**
     * @param UserRegistered $event
     */
    public function handle(UserRegistered $event)
    {
        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($event->user->email)
            ->setTemplate('patient.welcome')
            ->setTemplateModel([
                'action_url' => $event->user->emailVerificationURL(),
            ]);

        dispatch($transactionalEmailJob);
    }
}
