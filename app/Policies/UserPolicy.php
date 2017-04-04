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
        if ($user->isAdmin()) {
            return true;
        }
    }
    
    /**
     * @param User $user
     * @param User $target_user
     */
    public function view(User $user, User $target_user)
    {
        return $user->id == $target_user->id;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }
    
    /**
     * @param User $user
     * @param User $target_user
     * @return bool
     */
    public function update(User $user, User $target_user)
    {
        return $user->id == $target_user->id;
    }
}
