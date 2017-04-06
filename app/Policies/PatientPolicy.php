<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientPolicy
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
     * @param User    $user
     * @param Patient $patient
     * @return bool
     */
    public function view(User $user, Patient $patient)
    {
        return $user->id == $patient->user_id;
    }

    /**
     * Determine whether the user can create patients.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }
    
    /**
     * @param User    $user
     * @param Patient $patient
     * @return bool
     */
    public function update(User $user, Patient $patient)
    {
        return $user->id == $patient->user_id;
    }

    /**
     * Determine whether the user can delete the patient.
     *
     * @param  \App\User  $user
     * @param  \App\Patient  $patient
     * @return mixed
     */
    public function delete(User $user, Patient $patient)
    {
        //
    }
}
