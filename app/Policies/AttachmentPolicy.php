<?php

namespace App\Policies;

use App\Models\{Attachment, Patient, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return $user->isAdmin() ?: null;
    }

    public function get(User $user, Attachment $attachment)
    {
        return $user->isPractitioner() || $user->is($attachment->creator) || $user->is($attachment->patient->user);
    }

    public function update(User $user, Attachment $attachment)
    {
        return $user->isPractitioner() || $user->is($attachment->creator);
    }

    public function delete(User $user, Attachment $attachment)
    {
        return $user->isPractitioner() || $user->is($attachment->creator);
    }
}
