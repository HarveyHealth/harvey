<?php
namespace App\Listeners;

use App\Events\AppointmentComplete;
use App\Jobs\SendSMSMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class SendConsultationFeedback implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  AppointmentComplete  $event
     * @return void
     */
    public function handle(AppointmentComplete $event)
    {
        $appointment = $event->appointment;
        $user = $appointment->patient->user;
        $p_user = $appointment->practitioner->user;

        $message = "On a scale of 1-5, with 5 being the best, how valuable was Dr. {$p_user->full_name} on your last call in helping you meet your health goals?";

        // set the number for the right webhook so we can handle the response
        $from = config('services.twilio.feedback_number');

        return dispatch((new SendSMSMessage($user->phone, $message, $from))->delay(Carbon::now()->addMinutes(15)));
    }
}
