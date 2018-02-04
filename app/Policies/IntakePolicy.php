<?php

namespace App\Policies;

use App\Models\{Intake, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class IntakePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Intake $intake)
    {
        return $user->isPractitioner() || $intake->user->is($user);
    }
}
