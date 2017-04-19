<?php

namespace App\Observers;

use App\Events\UserRegistered;
use App\Models\User;

class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user)
    {
        event(new UserRegistered($user));
    }
}
