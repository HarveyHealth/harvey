<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Message;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param      $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        return $user->isAdmin() ?: null;
    }

    /**
     * @param User        $user
     * @param Message $message
     * @return bool
     */
    public function view(User $user, Message $message)
    {
        return $user->is($message->sender) || $user->is($message->recipient);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isAllowedToSendMessages();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function markAsRead(User $user, Message $message)
    {
        return $user->is($message->recipient);
    }

    /**
     * @param User        $user
     * @param Message $message
     * @return bool
     */
    public function delete(User $user, Message $message)
    {
        return ($user->is($message->sender) || $user->is($message->recipient))
                && $user->isPractitioner();
    }
}
