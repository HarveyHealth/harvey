<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\PractitionerScheduleOverride;

class PractitionerScheduleOverridePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return $user->isAdmin() ? : null;
    }

    public function view(User $user, PractitionerScheduleOverride $practitionerScheduleOverride)
    {
        return $user->isPractitioner() && ($user->practitioner->id == $practitionerScheduleOverride->practitioner_id);
    }

    public function edit(User $user, PractitionerScheduleOverride $practitionerScheduleOverride)
    {
        return $user->isPractitioner() && ($user->practitioner->id == $practitionerScheduleOverride->practitioner_id);
    }

    public function delete(User $user, PractitionerScheduleOverride $practitionerScheduleOverride)
    {
        return $user->isPractitioner() && ($user->practitioner->id == $practitionerScheduleOverride->practitioner_id);
    }

    public function create(User $user, PractitionerScheduleOverride $practitionerScheduleOverride)
    {
        return $user->isPractitioner() && ($user->practitioner->id == $practitionerScheduleOverride->practitioner_id);
    }
}
