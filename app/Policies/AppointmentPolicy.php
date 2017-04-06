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
        if ($user->isAdmin()) {
            return true;
        }
    }
    
    /**
     * @param User        $user
     * @param Appointment $appointment
     * @return bool
     */
    public function view(User $user, Appointment $appointment)
    {
        return $user->id == $appointment->patient->user->id ||
                $user->id == $appointment->practitioner->user->id;
    }
    
    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isPatient();
    }
    
    /**
     * @param User        $user
     * @param Appointment $appointment
     * @return bool
     */
    public function update(User $user, Appointment $appointment)
    {
        return $user->id == $appointment->patient->user->id &&
                $appointment->isNotLocked();
    }
    
    /**
     * Only the patient or an admin can cancel an appointment.
     * @param User        $user
     * @param Appointment $appointment
     * @return bool
     */
    public function delete(User $user, Appointment $appointment)
    {
        return $user->id == $appointment->patient->user->id &&
                $appointment->isNotLocked();
    }
}
