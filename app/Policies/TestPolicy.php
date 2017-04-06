<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Test;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestPolicy
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
     * @param Test $test
     * @return bool
     */
    public function view(User $user, Test $test)
    {
        return $user->id == $test->patient->user->id ||
                $user->id == $test->practitioner->user->id;
    }
}
