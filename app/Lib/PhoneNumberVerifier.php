<?php

namespace App\Lib;

use App\Models\User;

class PhoneNumberVerifier
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function sendVerificationCode()
    {
        // generate a 5 digit code
        $code = str_pad(rand(0,99999),5,0);

        // associate this phone number and code
        // in redis
        $key = $this->key();

        \Redis::set($key, $code);
        \Redis::expire($key, \App\Lib\TimeInterval::days(7)->toSeconds());

        // send a phone number validation message
        $message = "Your Harvey phone verification code is $code";

        $this->user->sendText($message);
    }

    public function isValid($code)
    {
        $key = $this->key();

        $value = \Redis::get($key);

        if ($value == $code) {
            return true;
        }

        return false;
    }

    protected function key()
    {
        $key = "phone_validation:{$this->user->id}:{$this->user->phone}";
        return $key;
    }

}
