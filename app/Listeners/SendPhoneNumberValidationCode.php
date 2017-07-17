<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\PhoneNumberVerifier;
use App\Events\PhoneNumberChanged;

class SendPhoneNumberValidationCode
{
    /**
     * Handle the event.
     *
     * @param  PhoneNumberChanged  $event
     * @return void
     */
    public function handle(PhoneNumberChanged $event)
    {
        return PhoneNumberVerifier::sendVerificationCode($event->user);
    }
}
