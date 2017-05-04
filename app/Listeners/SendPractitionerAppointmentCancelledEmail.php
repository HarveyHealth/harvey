<?php

namespace App\Listeners;

use App\Events\AppointmentCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;

class SendPractitionerAppointmentCancelledEmail implements ShouldQueue
{
    public function handle(AppointmentCancelled $event)
    {
        // Do something
    }
}
