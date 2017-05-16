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
        return $user->id == $message->sender->id || $user->id == $message->recipient->id;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * @param User        $user
     * @param Message $message
     * @return bool
     */
    public function delete(User $user, Message $message)
    {
        return false;
    }
}
