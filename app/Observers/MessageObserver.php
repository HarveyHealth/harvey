<?php

namespace App\Observers;

use App\Events\MessageCreated;
use App\Events\MessageDeleted;
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

    public function saved(Message $message)
    {
        // the phone is being updated
        if ($message->isDirty('deleted_at') && !empty($message->deleted_at)
            && $message->getOriginal('deleted_at') == null) {
                event(new MessageDeleted($message, currentUser(), $this->transformer));
        }
    }
}
