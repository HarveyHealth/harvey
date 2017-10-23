<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Lib\{TimeInterval, TransactionalEmail};
use Carbon;
use Illuminate\Support\Facades\Redis;

class SendUnreadMessageEmailNotificationsCommand extends Command
{
    const UNREAD_OLDER_THAN_MINUTES = 15;
    const LAST_PROCESSED_ID_REDIS_KEY = 'messages:send-unread-messages-notifications:last_processed_id';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:send-unread-messages-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends email notifications to users with unread messages.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Looking for unread messages.');

        $lastProcessedId = Redis::get(self::LAST_PROCESSED_ID_REDIS_KEY);
        $builder = Message::unread();

        if (is_numeric($lastProcessedId)) {
            $this->info("Last processed ID = {$lastProcessedId}.");
            $builder = $builder->idGreaterThan($lastProcessedId);
        } else {
            $this->info("Last processed ID not found.");
            $this->info('Looking for unread messages in the last ' . self::UNREAD_OLDER_THAN_MINUTES . ' minutes');
            $builder = $builder->createdAfter(Carbon::now()->subMinutes(self::UNREAD_OLDER_THAN_MINUTES));
        }

        $messages = $builder->get();

        foreach ($messages as $message) {
            $timezone = $message->recipient->timezone;
            $createdAt = $message->created_at->timezone($timezone);

            dispatch(TransactionalEmail::createJob()
                ->setTo($message->recipient->email)
                ->setTemplate('message.unread')
                ->setTemplateModel([
                    'sender_name' => $message->sender->full_name,
                    'message_date' => $createdAt->format('l F j'),
                    'message_time' => $createdAt->format('h:i A'),
                    'message_timezone' => $timezone,
                    'message_content' => $message->message,
                    'message_link' => config('app.url') . "/dashboard#/messages/{$message->id}",
            ]));
        }

        Redis::set(self::LAST_PROCESSED_ID_REDIS_KEY, $messages->last()->id ?? $lastProcessedId);
        Redis::expire(self::LAST_PROCESSED_ID_REDIS_KEY, TimeInterval::day()->toSeconds());

        $this->info("Done. [{$messages->count()} emails sent.]");
    }
}
