<?php

namespace App\Transformers\V1;

use App\Models\Message;
use League\Fractal\TransformerAbstract;

class MessageTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'sender', 'recipient'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Message $message)
    {
        return [
            'created_at' => $message->created_at,
            'id' => (string) $message->id,
            'is_sender_admin' => (boolean) $message->is_sender_admin,
            'message' => htmlentities($message->message),
            'read_at' => $message->read_at,
            'recipient_full_name' => $message->recipient->full_name,
            'recipient_image_url' => $message->recipient->image_url,
            'recipient_user_id' => (string) $message->recipient_user_id,
            'sender_full_name' => $message->sender->full_name,
            'sender_image_url' => $message->sender->image_url,
            'sender_user_id' => (string) $message->sender_user_id,
            'subject' => htmlentities($message->subject),
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
