<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\Message;

class MessageTransformer extends HarveyTransformer
{
    protected $availableIncludes = ['sender', 'recipient'];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Message $message)
    {
        return [
            'id' => cast_to_string($message->id),
            'created_at' => $message->created_at,
            'is_sender_admin' => (boolean) $message->is_sender_admin,
            'message' => cast_to_string($message->message),
            'read_at' => $message->read_at,
            'recipient_full_name' => $message->recipient->full_name,
            'recipient_image_url' => $message->recipient->image_url,
            'recipient_user_id' => cast_to_string($message->recipient_user_id),
            'sender_full_name' => $message->sender->full_name,
            'sender_image_url' => $message->sender->image_url,
            'sender_user_id' => cast_to_string($message->sender_user_id),
            'subject' => cast_to_string($message->subject),
        ];
    }

    /**
     * @param Message $message
     * @return mixed
     */
    public function includeSender(Message $message)
    {
        return $this->item($message->sender, new UserTransformer())->setResourceKey('sender');
    }

    /**
     * @param Message $message
     * @return mixed
     */
    public function includeRecipient(Message $message)
    {
        return $this->item($message->recipient, new UserTransformer())->setResourceKey('recipient');
    }
}
