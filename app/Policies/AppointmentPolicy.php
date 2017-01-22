<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the appointment.
     *
     * @param  User  $user
     * @param  Appointment  $appointment
     * @return mixed
     */
    public function view(User $user, Appointment $appointment)
    {
        return ($user->id === $appointment->patient_user_id) ||
            ($user->id === $appointment->practitioner_user_id);
    }

    /**
     * Determine whether the user can create appointments.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the appointment.
     *
     * @param  User  $user
     * @param  Appointment  $appointment
     * @return mixed
     */
    public function update(User $user, Appointment $appointment)
    {
        //
    }

    /**
     * Determine whether the user can delete the appointment.
     *
     * @param  User  $user
     * @param  Appointment  $appointment
     * @return mixed
     */
    public function delete(User $user, Appointment $appointment)
    {
        //
    }
}
