<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Notifications\Messages\SlackMessage;

class SlackNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $channel;
    protected $error;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $channel, $error = false)
    {
        $this->message = $message;
        $this->channel = $channel;
        $this->error = $error;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $message = new SlackMessage;
        $message = ($this->error) ? $message->error() : $message->success();
        return $message->content($this->message);
    }
}
