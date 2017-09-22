<?php

namespace App\Policies;

use App\Models\{Prescription, Patient, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class PrescriptionPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return $user->isAdmin() ?: null;
    }

    public function get(User $user, Prescription $prescription)
    {
        return $user->isPractitioner() || $user->is($prescription->creator) || $user->is($prescription->patient->user);
    }

    public function delete(User $user, Prescription $prescription)
    {
        return $user->isPractitioner() || $user->is($prescription->creator);
    }
}
