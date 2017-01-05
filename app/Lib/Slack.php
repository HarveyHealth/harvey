<?php

namespace App\Lib;

use Illuminate\Notifications\Notifiable;

/*
    Laravel requires that you send notifications to an object
    that has a Notifiable Trait. Generally, this is the User.
    This class is to allow sending Slack messages without
    involving a User object.

    Usage: (new Slack)->notify(new SomeNotification);
*/
class Slack
{
    use Notifiable;

    public function routeNotificationForSlack()
    {
        return config('services.slack.webhook_url');
    }

}
