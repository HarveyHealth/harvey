<?php

namespace App\Events;

use Illuminate\Broadcasting\{Channel, PrivateChannel, PresenceChannel, InteractsWithSockets};
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use League\Fractal\Serializer\JsonApiSerializer;
use App\Models\{Message, User};
use App\Transformers\V1\MessageTransformer;

class MessageDeleted implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

    protected $transformer;
    protected $serializer;
    protected $deleter;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message, MessageTransformer $transformer, User $deleter=null)
    {
        $this->message = $message;
        $this->transformer = $transformer;
        $this->deleter = $deleter;
        $this->serializer = new JsonApiSerializer(config('app.url') . '/api/v1');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        if ($this->deleter != null){
            return new PrivateChannel("App.User.{$this->deleter->id}");
        }
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
