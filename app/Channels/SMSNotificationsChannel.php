<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Lib\SMS;

class SMSNotificationsChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSMS($notifiable);

        // Send notification to the $notifiable instance...
        $phone = $notifiable->phone;

    }
}
