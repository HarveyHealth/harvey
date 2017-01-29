<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    
    /**
     * @param User $user
     * @param $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->superUser()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param  User  $user
     * @param  User  $target_user
     * @return bool
     */
    public function view(User $user, User $target_user)
    {
        if ($user->consultsWithUser($target_user)) {
            return true;
        }
        
        return $user->id === $target_user->id;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  User  $user
     * @param  User  $target_user
     * @return mixed
     */
    public function update(User $user, User $target_user)
    {
        return $user->id === $target_user->id;
    }
}
