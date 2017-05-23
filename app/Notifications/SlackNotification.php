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
    protected $level;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $channel, $level = 'info')
    {
        $this->message = $message;
        $this->channel = app()->environment(['staging', 'production']) ? $channel : config('services.slack.testing_channel');
        $this->level = $level;
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
        $message->level = $this->level;
        $message = $message->to($this->channel);
        return $message->content($this->message);
    }
}
