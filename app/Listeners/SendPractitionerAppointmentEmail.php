<?php

namespace App\Listeners;

use App\Events\AppointmentScheduled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPractitionerAppointmentEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AppointmentScheduled  $event
     * @return void
     */
    public function handle(AppointmentScheduled $event)
    {
        //
    }
}
