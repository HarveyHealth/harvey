<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * @param User $user
     * @param User $targetUser
     * @return bool
     */
    public function view(User $user, User $targetUser)
    {
        return $user->isPractitioner() || $user->is($targetUser);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * @param User $user
     * @param User $targetUser
     * @return bool
     */
    public function update(User $user, User $targetUser)
    {
        return $user->is($targetUser);
    }
}
