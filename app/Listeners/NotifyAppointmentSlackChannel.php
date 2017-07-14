<?php

namespace App\Listeners;

use App\Events\AppointmentScheduled;
use App\Lib\Slack;
use App\Notifications\SlackNotification;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAppointmentSlackChannel implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  AppointmentScheduled  $event
     * @return void
     */
    public function handle(AppointmentScheduled $event)
    {
        $patient = $event->appointment->patient;
        $practitioner = $event->appointment->practitioner;
        $time = $event->appointment->appointment_at;
        $time->timezone = 'America/Los_Angeles';

        $message = '*[New Appointment]* Patient: *' . $patient->user->full_name . '* with ' . $practitioner->user->full_name . ' on ' . $time->format('M j') . ' at ' . $time->format('g:ia');

        (new Slack())->notify(new SlackNotification($message, 'practitioners'));
    }
}
