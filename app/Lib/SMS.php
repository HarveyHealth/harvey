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

    public function sendMessageToUser(User $user, string $message)
    {
        return $this->sendMessageToNumber($user->phone, $message);
    }

    public function sendMessageToNumber($number, string $message)
    {
        if (isNotStgOrProd()) {
            return Log::info("Faking sending text message to {$number} with message: {$message}");
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
