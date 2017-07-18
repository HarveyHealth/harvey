<?php

namespace App\Observers;

use App\Events\PhoneNumberChanged;
use App\Models\User;

class UserObserver
{
    public function updating(User $user)
    {
        if ($user->isDirty('phone')) {
            $user->phone_verified_at = null;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
    }

    public function saved(User $user)
    {
        if ($user->isDirty('phone') && !empty($user->phone)) {
                event(new PhoneNumberChanged($user, $user->getOriginal('phone')));
        }
    }
}
