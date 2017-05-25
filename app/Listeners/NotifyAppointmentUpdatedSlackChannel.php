<?php

namespace App\Listeners;

use App\Events\AppointmentUpdated;
use App\Lib\Slack;
use App\Notifications\SlackNotification;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAppointmentUpdatedSlackChannel implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  AppointmentUpdated  $event
     * @return void
     */
    public function handle(AppointmentUpdated $event)
    {
        $appointment = $event->appointment;
        $original_appointment_at = Carbon::parse($appointment->getOriginal('appointment_at'));

        $slackTimezone = 'America/Los_Angeles';

        $patient = $appointment->patient;
        $practitioner = $appointment->practitioner;
        $appointment_at = $appointment->appointment_at;
        $appointment_at->timezone = $slackTimezone;
        $original_appointment_at->timezone = $slackTimezone;

        $message = ":white_circle: *[Appointment Updated]* Patient: *{$patient->user->fullName()}* with {$practitioner->user->fullName()},";
        $message .= " new appointment on {$appointment_at->format('M j')} at {$appointment_at->format('g:ia')}";
        $message .= " (original was {$original_appointment_at->format('M j')} at {$original_appointment_at->format('g:ia')}).";

        (new Slack())->notify(new SlackNotification($message, 'operations'));
    }
}
