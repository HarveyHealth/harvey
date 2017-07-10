<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\{Channel, InteractsWithSockets, PresenceChannel, PrivateChannel};
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\User;

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
    public function __construct(User $user, $old_phone)
    {
        $this->user = $user;
        $this->old_phone = $old_phone;
    }
}
