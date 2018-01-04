<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\PractitionerSchedule;

class PractitionerSchedulePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return $user->isAdmin() ? : null;
    }

    public function view(User $user, PractitionerSchedule $practitionerSchedule)
    {
        return $user->isPractitioner() && ($user->practitioner->id == $practitionerSchedule->practitioner_id);
    }

    public function edit(User $user, PractitionerSchedule $practitionerSchedule)
    {
        return $user->isPractitioner() && ($user->practitioner->id == $practitionerSchedule->practitioner_id);
    }

    public function delete(User $user, PractitionerSchedule $practitionerSchedule)
    {
        return $user->isPractitioner() && ($user->practitioner->id == $practitionerSchedule->practitioner_id);
    }

    public function create(User $user, PractitionerSchedule $practitionerSchedule)
    {
        return $user->isPractitioner() && ($user->practitioner->id == $practitionerSchedule->practitioner_id);
    }
}
