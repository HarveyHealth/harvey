<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param      $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        return $user->isAdmin() ?: null;
    }

    /**
     * @param User        $user
     * @param Appointment $appointment
     * @return bool
     */
    public function view(User $user, Appointment $appointment)
    {
        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;

        return $user->is($patient->user) || $user->is($practitioner->user);
    }

    /**
     * @param User        $user
     * @param Appointment $appointment
     * @return bool
     */
    public function update(User $user, Appointment $appointment)
    {
        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;

        if ($user->isPatient() && ($appointment->isLocked() || $appointment->isNotPending())) {
            return false;
        }

        return $user->is($patient->user) || $user->is($practitioner->user);
    }

    /**
     * Only the patient or an admin can cancel an appointment.
     * @param User        $user
     * @param Appointment $appointment
     * @return bool
     */
    public function delete(User $user, Appointment $appointment)
    {
        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;

        return ($user->is($patient->user) || $user->is($practitioner->user)) && $appointment->isNotLocked();
    }
}
