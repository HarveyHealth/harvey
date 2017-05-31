<?php

namespace App\Listeners;

use App\Events\AppointmentCanceled;
use App\Lib\Slack;
use App\Notifications\SlackNotification;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAppointmentCanceledSlackChannel implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  AppointmentCanceled  $event
     * @return void
     */
    public function handle(AppointmentCanceled $event)
    {
        $patient = $event->appointment->patient;
        $practitioner = $event->appointment->practitioner;
        $appointment_at = $event->appointment->appointment_at;
        $appointment_at->timezone = 'America/Los_Angeles';

        $message = "*[Appointment Canceled]*: Patient: *{$patient->user->fullName()}* with {$practitioner->user->fullName()} on {$appointment_at->format('M j')} at {$appointment_at->format('g:ia')}";

        (new Slack())->notify(new SlackNotification($message, 'operations'));
    }
}
