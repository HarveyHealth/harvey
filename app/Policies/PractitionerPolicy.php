<?php

namespace App\Policies;

use App\Models\Practitioner;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PractitionerPolicy
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
     * @param User         $user
     * @param Practitioner $practitioner
     * @return bool
     */
    public function view(User $user, Practitioner $practitioner)
    {
        return $practitioner->enabled;
    }
    
    
}
