<?php

namespace App\Policies;

use App\Models\{User, LabTestResult};
use Illuminate\Auth\Access\HandlesAuthorization;

class LabTestResultPolicy
{
    use HandlesAuthorization;

    public function view(User $user, LabTestResult $lab_test_result)
    {
        return $user->isPractitioner() || $user->is($lab_test_result->labTest->patient->user);
    }

    public function update(User $user, LabTestResult $lab_test_result)
    {
        return $user->isPractitioner();
    }

    public function delete(User $user, LabTestResult $lab_test_result)
    {
        return $user->isPractitioner();
    }

}
