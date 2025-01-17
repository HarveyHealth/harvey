<?php

namespace App\Listeners;

use App\Events\AppointmentCanceled;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteAppointmentCalendarEvent implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  AppointmentScheduled  $event
     * @return void
     */
    public function handle(AppointmentCanceled $event)
    {
        return $event->appointment->deleteFromCalendar();
    }
}
