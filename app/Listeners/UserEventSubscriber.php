<?php

namespace App\Listeners;

class UserEventSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventSubscriber@onUserLogout'
        );

        $events->listen(
            'Illuminate\Auth\Events\Registered',
            'App\Listeners\UserEventSubscriber@onRegister'
        );
    }


    /**
     * Handle user login events.
     */
    public function onUserLogin($event) {
        \Log::info('User ' . $event->user->id . ' has logged in.');
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event) {
        \Log::info('User ' . $event->user->id . ' has logged out.');
    }

    /**
     * Handle user logout events.
     */
    public function onRegister($event) {
        \Log::info('User ' . $event->user->id . ' has registered.');
    }
}
