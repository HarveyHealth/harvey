<?php

namespace App\Listeners;

use App\Events\UserAccountCreated;
use App\Mail\Welcome;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewUserEmail
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
     * @param  UserAccountCreated  $event
     * @return void
     */
    public function handle(UserAccountCreated $event)
    {
        $user = $event->user;
        Mail::to($user)->send(new Welcome($user));
    }
}
