<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Message;
use App\Lib\TransactionalEmail;

class NotifyNewMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->message->read_at)){
            $timezone = $this->message->recipient->timezone;
            $createdAt = $this->message->created_at->timezone($timezone);

            dispatch(TransactionalEmail::createJob()
                ->setTo($this->message->recipient->email)
                ->setTemplate('message.unread')
                ->setTemplateModel([
                    'sender_name' => $this->message->sender->full_name,
                    'message_date' => $createdAt->format('l F j'),
                    'message_time' => $createdAt->format('h:i A'),
                    'message_timezone' => $timezone,
                    'message_content' => $this->message->message,
                    'message_link' => config('app.url') . "/dashboard#/messages/{$this->message->id}",
                ]));
        }
    }
}
