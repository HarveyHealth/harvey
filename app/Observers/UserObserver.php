<?php

namespace App\Observers;

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

    public function saved(User $user)
    {
        // the phone is being updated
        if ($user->isDirty('phone')) {
            if (!empty($user->phone)) {
                event(new PhoneNumberChanged($user, $user->getOriginal('phone')));
            }
        }
    }
}
