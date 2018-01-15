<?php

namespace App\Policies;

use App\Models\{Patient, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientPolicy
{
    use HandlesAuthorization;

    /**
     * @param User    $user
     * @param Patient $patient
     * @return bool
     */
    public function view(User $user, Patient $patient)
    {
        return $user->isAdmin() || $patient->user->is($user) || $user->isPractitioner();
    }

    /**
     * @param User    $user
     * @param Patient $patient
     * @return bool
     */
    public function update(User $user, Patient $patient)
    {
        return $user->isAdmin() || $patient->user->is($user) || $user->isPractitioner();
    }

    /**
     * Determine whether the user can store an Attachment to the patient.
     *
     * @param  \App\User  $user
     * @param  \App\Patient  $patient
     * @return mixed
     */
    public function storeAttachment(User $user, Patient $patient)
    {
        return $user->isAdmin() || $user->is($patient->user) || $user->isPractitioner();
    }

    /**
     * Determine whether the user can store a Prescription to the patient.
     *
     * @param  \App\User  $user
     * @param  \App\Patient  $patient
     * @return mixed
     */
    public function storePrescription(User $user, Patient $patient)
    {
        return $user->isAdmin() || $user->is($patient->user) || $user->isPractitioner();
    }

    /**
     * Determine whether the user can store a SoapNote to the patient.
     *
     * @param  \App\User  $user
     * @param  \App\Patient  $patient
     * @return mixed
     */
    public function storeSoapNote(User $user, Patient $patient)
    {
        return $user->isAdmin() || $user->is($patient->user) || $user->isPractitioner();
    }

    public function includeAttachments(User $user, Patient $patient)
    {
        return $user->is($patient->user) || $user->isPractitioner();
    }

    public function includeSoapNotes(User $user, Patient $patient)
    {
        return $user->is($patient->user) || $user->isPractitioner();
    }

    public function includeIntake(User $user, Patient $patient)
    {
        return $user->is($patient->user) || $user->isPractitioner();
    }

    public function includePrescriptions(User $user, Patient $patient)
    {
        return $user->is($patient->user) || $user->isPractitioner();
    }
}
