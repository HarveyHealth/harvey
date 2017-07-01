<?php

namespace App\Http\Traits;

trait Textable
{
    public function sendText($message)
    {
        dispatch(new \App\Jobs\SendSMSMessage($this->phone, $message));
    }
}
