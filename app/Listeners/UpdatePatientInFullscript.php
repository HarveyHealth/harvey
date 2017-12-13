<?php

namespace App\Listeners;

use App\Events\UserUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\UpdateFullscriptPatient;


class UpdatePatientInFullscript implements ShouldQueue
{
    /**
     * @param UserRegistered $event
     */
    public function handle(UserUpdated $event)
    {
        $user = $event->user;
        dispatch(new UpdateFullscriptPatient($user));
    }
}
