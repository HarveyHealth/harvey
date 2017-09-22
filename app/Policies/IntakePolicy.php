<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param      $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * @param User    $user
     * @param Patient $patient
     * @return bool
     */
    public function view(User $user, Patient $patient)
    {
        return $patient->user->is($user) || $user->isPractitioner();
    }

    /**
     * @param User    $user
     * @param Patient $patient
     * @return bool
     */
    public function update(User $user, Patient $patient)
    {
        return $patient->user->is($user) || $user->isPractitioner();
    }

    /**
     * Determine whether the user can add, delete or update an Attachment of the patient.
     *
     * @param  \App\User  $user
     * @param  \App\Patient  $patient
     * @return mixed
     */
    public function handleAttachment(User $user, Patient $patient)
    {
        return $patient->user->is($user) || $user->isPractitioner();
    }

    /**
     * Determine whether the user can add, delete or update a Prescription of the patient.
     *
     * @param  \App\User  $user
     * @param  \App\Patient  $patient
     * @return mixed
     */
    public function handlePrescription(User $user, Patient $patient)
    {
        return $user->isPractitioner();
    }

    /**
     * Determine whether the user can add, delete or update a SOAP Note of the patient.
     *
     * @param  \App\User  $user
     * @param  \App\Patient  $patient
     * @return mixed
     */
    public function handleSoapNote(User $user, Patient $patient)
    {
        return $user->isPractitioner();
    }
}
