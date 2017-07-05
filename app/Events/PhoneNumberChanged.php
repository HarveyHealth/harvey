<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PhoneNumberChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $old_phone;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(\App\Models\User $user, $old_phone)
    {
        $this->user = $user;
        $this->old_phone = $old_phone;
    }
}
