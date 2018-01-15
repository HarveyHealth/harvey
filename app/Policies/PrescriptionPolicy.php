<?php

namespace App\Policies;

use App\Models\{Prescription, Patient, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class PrescriptionPolicy
{
    use HandlesAuthorization;

    public function get(User $user, Prescription $prescription)
    {
        return $user->isPractitioner() || $user->is($prescription->creator) || $user->is($prescription->patient->user);
    }

    public function update(User $user, Prescription $prescription)
    {
        return $user->isPractitioner() || $user->is($prescription->creator);
    }

    public function delete(User $user, Prescription $prescription)
    {
        return $user->isPractitioner() || $user->is($prescription->creator);
    }
}
