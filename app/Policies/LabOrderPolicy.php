<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LabOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class LabOrderPolicy
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
     * @param LabOrder $labOrder
     * @return bool
     */
    public function view(User $user, LabOrder $labOrder)
    {
        return $user->is($labOrder->patient->user) || $user->is($labOrder->practitioner->user);
    }

    /**
     * Determine whether the user can create LabOrders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * @param User    $user
     * @param LabOrder $labOrder
     * @return bool
     */
    public function update(User $user, LabOrder $labOrder)
    {
        return $user->is($labOrder->practitioner->user) || $user->is($labOrder->patient->user);
    }

    /**
     * Determine whether the user can delete the LabOrder.
     *
     * @param  \App\User  $user
     * @param  \App\LabOrder  $labOrder
     * @return mixed
     */
    public function delete(User $user, LabOrder $labOrder)
    {
        return false;
    }
}
