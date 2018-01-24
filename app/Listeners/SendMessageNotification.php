<?php
namespace App\Listeners;

use App\Events\MessageCreated;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\Jobs\NotifyNewMessage;

class SendMessageNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  MessageCreated  $event
     * @return void
     */
    public function handle(MessageCreated $event)
    {
        $message = $event->message;

        return dispatch((new NotifyNewMessage($message))
            ->delay(Carbon::now()->addMinutes(Message::NOTIFY_AFTER_MINUTES)));
    }
}
