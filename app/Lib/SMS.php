<?php

namespace App\Lib;

use App\Models\User;
use Twilio\Rest\Client as Twilio;
use Log;

class SMS
{
    protected $twilio;

    public function __construct(Twilio $twilio)
    {
        $this->twilio = $twilio;
    }

    public function sendMessageToUser(User $user, string $message, string $from = null)
    {
        $from = $from ?? config('services.twilio.sms_number');
        return $this->sendMessageToNumber($user->phone, $message, $from);
    }

    public function sendMessageToNumber($number, string $message, string $from = null)
    {
        if (!app()->environment(['production','staging'])) {
            return Log::info("Faking sending text message to {$number} with message: {$message}");
        }

        $from = $from ?? config('services.twilio.sms_number');

        $this->twilio->messages->create(
            $number,
            [
                'from' => $from,
                'body' => $message
            ]
        );
    }
}
