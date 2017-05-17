<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Events\AppointmentCanceled;

class AppointmentObserver
{
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
