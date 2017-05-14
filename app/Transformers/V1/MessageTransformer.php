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
            'id' => (string) $message->id,
            'message' => $message->message,
            'sender_user_id' => (string) $message->sender_user_id,
            'recipient_user_id' => (string) $message->recipient_user_id,
            'is_admin' => $message->is_sender_admin,
            'read_at' => $message->read_at,
            'created_at' => $message->created_at,
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
