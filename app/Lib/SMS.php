<?php

namespace App\Lib;

use Twilio\Rest\Client as Twilio;

class SMS
{
    protected $twilio;

    function __construct() {

        $this->twilio = new Twilio(config('services.twilio.sid'), config('services.twilio.token'));
    }

    function sendMessageToUser($user, $message) {
        $phone = $user->phone;
        $this->sendMessageToNumber($phone, $message);
    }

    function sendMessageToNumber($number, $message) {

        if (!app()->environment(['production','staging'])) {
            \Log::info('Faking sending text message to ' . $number . ' with message: ' . $message);
            return;
        }

        $this->twilio->messages->create(
            $number,
            [
                'from' => config('services.twilio.sms_number'),
                'body' => $message
            ]
        );
    }
}
