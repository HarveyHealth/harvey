<?php

namespace App\Policies;

use App\Models\{Patient, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class IntakePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Patient $patient)
    {
        return $user->isPractitioner() || ($user->isPatient() && $user->patient->intake_token == $patient->intake_token);
    }
}
