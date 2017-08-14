<?php

namespace App\Listeners;

use App\Events\AppointmentScheduled;

class CreateAppointmentCalendarEvent
{
    /**
     * Handle the event.
     *
     * @param  AppointmentScheduled  $event
     * @return void
     */
    public function handle(AppointmentScheduled $event)
    {
        return $event->appointment->addToCalendar();
    }
}
