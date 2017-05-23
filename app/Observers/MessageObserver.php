<?php

namespace App\Observers;

use App\Models\Message;

class MessageObserver
{
    /**
     * Listen to the Message created event.
     *
     * @param  Message $message
     * @return void
     */
    public function created(Message $message)
    {
        event(new MessageCreated($message));
    }
}
