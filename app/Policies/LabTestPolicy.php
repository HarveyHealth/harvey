<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LabTest;
use Illuminate\Auth\Access\HandlesAuthorization;

class LabTestPolicy
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
     * @param User    $user
     * @param LabTest $labTest
     * @return bool
     */
    public function view(User $user, LabTest $labTest)
    {
        return $user->is($labTest->patient->user) || $user->is($labTest->practitioner->user);
    }

    /**
     * Determine whether the user can create LabTests.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true; //WIP we need to check if lab_order_id belongs to Patient
        return $user->isAdminOrPractitioner();
    }

    /**
     * @param User    $user
     * @param LabTest $labTest
     * @return bool
     */
    public function update(User $user, LabTest $labTest)
    {
        return $user->is($labTest->practitioner->user) || $user->is($labTest->patient->user);
    }

    /**
     * Determine whether the user can delete the LabTest.
     *
     * @param  \App\User  $user
     * @param  \App\LabTest  $labTest
     * @return mixed
     */
    public function delete(User $user, LabTest $labTest)
    {
        return false;
    }
}
