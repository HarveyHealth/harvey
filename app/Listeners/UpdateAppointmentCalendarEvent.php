<?php

namespace App\Listeners;

use App\Events\AppointmentUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateAppointmentCalendarEvent implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  AppointmentScheduled  $event
     * @return void
     */
    public function handle(AppointmentUpdated $event)
    {
        return $event->appointment->updateOnCalendar();
    }
}
