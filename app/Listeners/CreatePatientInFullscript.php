<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\CreateFullscriptPatient;


class CreatePatientInFullscript implements ShouldQueue
{
    /**
     * @param UserRegistered $event
     */
    public function handle(UserRegistered $event)
    {
        $user = $event->user;
        dispatch(new CreateFullscriptPatient($user));
    }
}
