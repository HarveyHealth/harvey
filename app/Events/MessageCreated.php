<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use League\Fractal\Serializer\JsonApiSerializer;
use App\Models\Message;
use App\Transformers\V1\MessageTransformer;

class MessageCreated implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

    protected $transformer;
    protected $serializer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message, MessageTransformer $transformer)
    {
        $this->message = $message;
        $this->transformer = $transformer;
        $this->serializer = new JsonApiSerializer(config('app.url') . '/api/v1');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("App.User.{$this->message->recipient->id}");
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $fractal = fractal()->item($this->message)->withResourceName('messages');
        return $fractal->transformWith($this->transformer)->serializeWith($this->serializer)->toArray();
    }
}
