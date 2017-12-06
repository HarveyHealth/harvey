<?php

namespace App\Http\Traits;

use App\Jobs\SendSMSMessage;

trait Textable
{
    public function sendText(string $message, int $delay = 0)
    {
        $job = new SendSMSMessage($this->phone, $message);

        if ($delay > 0){
            $job->delay($delay);
        }

        return dispatch($job);
    }
}
