<?php

namespace App\Observers;

use App\Events\UserUpdated;
use App\Events\PhoneNumberChanged;
use App\Models\User;

class UserObserver
{
    public function updating(User $user)
    {
        // the phone is being updated
        if ($user->isDirty('phone')) {
            $user->phone_verified_at = null;
        }
    }

    public function updated(User $user)
    {
        event(new UserUpdated($user));
    }

    public function saved(User $user)
    {
        // the phone is being updated
        if ($user->isDirty('phone') && !empty($user->phone)) {
                event(new PhoneNumberChanged($user, $user->getOriginal('phone')));
        }
    }
}
