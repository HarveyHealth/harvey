<?php

namespace Tests\Feature;

use App\Console\Commands\SendUnreadMessageEmailNotificationsCommand;
use App\Models\{Message, Patient};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Carbon, ResponseCode;
use Illuminate\Support\Facades\Redis;


class UnreadMessageEmailNotificationsTest extends TestCase
{
    use DatabaseMigrations;

    protected function getMessageEmailCommandOutput()
    {
        return $this->getCommandOutput('messages:send-unread-messages-notifications');
    }

    public function test_it_finds_unread_messages_older_than_newer_than_no_redis()
    {
        Redis::del(SendUnreadMessageEmailNotificationsCommand::LAST_PROCESSED_ID_REDIS_KEY);

        $newer_than = SendUnreadMessageEmailNotificationsCommand::UNREAD_NEWER_THAN_MINUTES;
        $older_than = SendUnreadMessageEmailNotificationsCommand::UNREAD_OLDER_THAN_MINUTES;
        $should_send = 0;
        $email_address = '';

        // create unread Messages sent a different times from now
        foreach ([-31, -21, -16, -11, -6, 6, 11] as $minutesFromNow) {
            $message = factory(Message::class)->create([
                'created_at' => Carbon::parse("{$minutesFromNow} minutes"),
                'read_at' => null,
            ]);
            if (Carbon::parse("{$minutesFromNow} minutes")->gt(Carbon::parse("-$newer_than minutes"))
                and
                Carbon::parse("{$minutesFromNow} minutes")->lt(Carbon::parse("-$older_than minutes"))){

                $should_send++;
                $email_address = $message->recipient->email;
            }
        }

        // creates a read message sent 5 minutes ago
        factory(Message::class)->create([
            'created_at' => Carbon::parse("-5 minutes"),
            'read_at' => Carbon::now(),
        ]);

        $output = $this->getMessageEmailCommandOutput();

        $this->assertEquals('Looking for unread messages.', $output[0]);
        $this->assertEquals('Last processed ID not found.', $output[1]);
        $this->assertEquals("Done. [{$should_send} emails sent.]", $output[3]);

        $this->assertEmailWasSentTo($email_address);
    }

    public function test_it_continues_with_the_last_processed_message_older_than()
    {
        Redis::del(SendUnreadMessageEmailNotificationsCommand::LAST_PROCESSED_ID_REDIS_KEY);

        $newer_than = SendUnreadMessageEmailNotificationsCommand::UNREAD_NEWER_THAN_MINUTES;
        $older_than = SendUnreadMessageEmailNotificationsCommand::UNREAD_OLDER_THAN_MINUTES;
        $should_send = 0;
        $last_processed_id = 0;


        // send messages without Redis index of last processed id
        foreach ([-31, -21, -16, -11, -6, 6, 11] as $minutesFromNow) {
            $message = factory(Message::class)->create([
                'created_at' => Carbon::parse("{$minutesFromNow} minutes"),
                'read_at' => null,
            ]);

            if (Carbon::parse("{$minutesFromNow} minutes")->gt(Carbon::parse("-$newer_than minutes"))
                and
                Carbon::parse("{$minutesFromNow} minutes")->lt(Carbon::parse("-$older_than minutes"))){
                // saves the last processed that is going to be taken from Redis
                $last_processed_id = $message->id;
            }
        }

        // send the emails and saves last processed id in redis
        $output = $this->getMessageEmailCommandOutput();

        // create unread Messages sent a different times from now
        foreach ([-31, -21, -16, -11, -6, 6, 11] as $minutesFromNow) {
            if (Carbon::parse("{$minutesFromNow} minutes")->lt(Carbon::parse("-$older_than minutes"))){
                $should_send++;
            }
            factory(Message::class)->create([
                'created_at' => Carbon::parse("{$minutesFromNow} minutes"),
                'read_at' => null,
            ]);
        }

        // send emails
        $output = $this->getMessageEmailCommandOutput();

        $this->assertEquals("Last processed ID = {$last_processed_id}.", $output[1]);
        $this->assertEquals("Done. [{$should_send} emails sent.]", $output[2]);
        Redis::del(SendUnreadMessageEmailNotificationsCommand::LAST_PROCESSED_ID_REDIS_KEY);
    }

    public function test_email_is_sent_if_message_is_almost_15_minutes_in_the_past()
    {
        Redis::del(SendUnreadMessageEmailNotificationsCommand::LAST_PROCESSED_ID_REDIS_KEY);
        $patient = factory(Patient::class)->create();

        $newer_than = SendUnreadMessageEmailNotificationsCommand::UNREAD_NEWER_THAN_MINUTES - 1;

        $message = factory(Message::class)->create([
            'created_at' => Carbon::parse("-{$newer_than} minutes 59 seconds"),
            'read_at' => null,
            'recipient_user_id' => $patient->user->id,
        ]);
        $timezone = $message->recipient->timezone;
        $createdAt = $message->created_at->timezone($timezone);

        $output = $this->getMessageEmailCommandOutput();

        $this->assertEmailWasSentTo($message->recipient->email);
        $this->assertEmailTemplateNameWas('message.unread');
        $this->assertEmailTemplateDataWas([
            'sender_name' => $message->sender->full_name,
            'message_date' => $createdAt->format('l F j'),
            'message_time' => $createdAt->format('h:i A'),
            'message_timezone' => $timezone,
            'message_content' => $message->message,
            'message_link' => config('app.url') . "/dashboard#/messages/{$message->id}",
        ]);

        $this->assertEquals('Done. [1 emails sent.]', $output[3]);
    }

    public function test_email_is_not_sent_if_message_is_5_minutes_in_the_past()
    {
        Redis::del(SendUnreadMessageEmailNotificationsCommand::LAST_PROCESSED_ID_REDIS_KEY);
        $patient = factory(Patient::class)->create();

        $message = factory(Message::class)->create([
            'created_at' => Carbon::parse("-5 minutes"),
            'read_at' => null,
            'recipient_user_id' => $patient->user->id,
        ]);
        $timezone = $message->recipient->timezone;
        $createdAt = $message->created_at->timezone($timezone);

        $output = $this->getMessageEmailCommandOutput();

        $this->assertEquals('Done. [0 emails sent.]', $output[3]);
    }
}
