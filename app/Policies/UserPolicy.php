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
     * @return bool
     */
    public function view(User $user, User $target_user)
    {
        return $user->id == $target_user->id;
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
     * @param User $target_user
     * @return bool
     */
    public function update(User $user, User $target_user)
    {
        return $user->id == $target_user->id;
    }
}
