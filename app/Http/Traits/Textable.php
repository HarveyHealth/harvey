<?php

namespace App\Http\Traits;

use App\Jobs\SendSMSMessage;

trait Textable
{
    public function sendText(string $message)
    {
        return dispatch(new SendSMSMessage($this->phone, $message));
    }
}
