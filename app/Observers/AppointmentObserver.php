<?php

namespace App\Observers;

use App\Events\AppointmentCanceled;
use App\Events\AppointmentUpdated;
use App\Models\Appointment;

class AppointmentObserver
{
    /**
     * Listen to the Appointment creating event.
     *
     * @param  Appointment $appointment
     * @return void
     */
    public function creating(Appointment $appointment)
    {
        if ($appointment->isFirst()) {
            $appointment->type_id = Appointment::FIRST_APPOINTMENT_TYPE_ID;
        }
    }

    /**
     * Listen to the Appointment updating event.
     *
     * @param  Appointment $appointment
     * @return void
     */
    public function updating(Appointment $appointment)
    {
        if ($appointment->isDirty('status_id') && Appointment::CANCELED_STATUS_ID == $appointment->status_id) {
            event(new AppointmentCanceled($appointment));
        } elseif ($appointment->isDirty('appointment_at')) {
            event(new AppointmentUpdated($appointment));
        }
    }

    /**
     * Listen to the Appointment delete event.
     *
     * @param  Appointment $appointment
     * @return void
     */
    public function deleted(Appointment $appointment)
    {
        event(new AppointmentCanceled($appointment));
    }
}
