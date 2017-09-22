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
        $this->level = $level;

        if (isNotProd()) {
            $this->channel = config('services.slack.testing_channel');
            $this->message = '[' . ucfirst(app()->environment());
            if (isLocal()) {
                $this->message .= ' (_' . gethostname() . '_)';
            }
            $this->message .= "] {$message}";
        } else {
            $this->message = $message;
            $this->channel = $channel;
        }
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
