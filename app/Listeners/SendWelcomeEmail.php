<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SendTransactionalEmail;

class SendWelcomeEmail implements ShouldQueue
{
    protected $sendTransactionalEmail;

    public function __construct(SendTransactionalEmail $sendTransactionalEmail)
    {
        $this->sendTransactionalEmail = $sendTransactionalEmail;
    }

    /**
     * @param UserRegistered $event
     */
    public function handle(UserRegistered $event)
    {
        $this->sendTransactionalEmail
            ->setTo($event->user->email)
            ->setTemplate('patient.welcome')
            ->setTemplateModel([
                'name' => $event->user->fullName(),
                'action_url' => $event->user->emailVerificationURL(),
            ]);

        dispatch($this->sendTransactionalEmail);
    }
}
