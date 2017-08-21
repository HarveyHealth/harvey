<?php

namespace App\Observers;

use App\Events\{AppointmentCanceled, AppointmentScheduled, AppointmentUpdated, AppointmentComplete};
use App\Models\Appointment;

class AppointmentObserver
{
    /**
     * Listen to the Appointment delete event.
     *
     * @param  Appointment $appointment
     * @return void
     */
    public function created(Appointment $appointment)
    {
        event(new AppointmentScheduled($appointment));
    }

    /**
     * Listen to the Appointment updating event.
     *
     * @param  Appointment $appointment
     * @return void
     */
    public function updating(Appointment $appointment)
    {
        if ($appointment->isDirty('status_id')) {

            switch($appointment->status_id) {
                case Appointment::CANCELED_STATUS_ID:
                    event(new AppointmentCanceled($appointment));
                    break;

                case Appointment::COMPLETE_STATUS_ID:
                    event(new AppointmentComplete($appointment));
                    break;
            }
        }

        if ($appointment->isDirty('appointment_at')) {
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
