<?php

namespace App\Policies;

use App\Models\{User, LabTest};
use Illuminate\Auth\Access\HandlesAuthorization;

class LabTestPolicy
{
    use HandlesAuthorization;

    public function view(User $user, LabTest $labTest)
    {
        return $user->isAdmin() || $user->is($labTest->patient->user) || $user->is($labTest->practitioner->user);
    }

    public function update(User $user, LabTest $labTest)
    {
        return $user->isAdmin() || $user->is($labTest->practitioner->user) || $user->is($labTest->patient->user);
    }

    public function delete(User $user, LabTest $labTest)
    {
        return $user->isAdmin();
    }

    public function storeResult(User $user, LabTest $labTest)
    {
        return $user->is($labTest->practitioner->user) || $user->is($labTest->patient->user);
    }
}
