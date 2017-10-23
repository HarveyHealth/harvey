<?php

namespace App\Lib;

use App\Models\User;
use App\Lib\TimeInterval;
use Illuminate\Support\Facades\Redis;

class PhoneNumberVerifier
{
    public static function sendVerificationCode(User $user)
    {
        // generate a 5 digit code
        $code = str_pad(rand(0, 99999), 5, 0);

        // associate this phone number and code
        // in redis
        $key = static::key($user);

        Redis::set($key, $code);
        Redis::expire($key, TimeInterval::days(7)->toSeconds());

        return $user->sendText("Your Harvey phone verification code is {$code}");
    }

    public static function isValid(User $user, $code)
    {
        return Redis::get(static::key($user)) == $code;
    }

    protected static function key(User $user)
    {
        return "phone_validation:{$user->id}:{$user->phone}";
    }

}
