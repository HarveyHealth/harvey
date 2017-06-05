<?php

namespace App\Observers;

use App\Events\MessageCreated;
use App\Models\Message;
use App\Transformers\V1\MessageTransformer;

class MessageObserver
{
    public function __construct(MessageTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Listen to the Message created event.
     *
     * @param  Message $message
     * @return void
     */
    public function created(Message $message)
    {
        event(new MessageCreated($message, $this->transformer));
    }
}
