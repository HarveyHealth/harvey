<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\PhoneNumberVerifier;
use App\Events\PhoneNumberChanged;

class SendPhoneNumberValidationCode
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PhoneNumberChanged  $event
     * @return void
     */
    public function handle(PhoneNumberChanged $event)
    {
        $user = $event->user;

        $verifier = new PhoneNumberVerifier($user);
        $verifier->sendVerificationCode();
    }
}
