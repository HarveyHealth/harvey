<?php

namespace Tests\Feature;

use App\Console\Commands\SendUnreadMessageEmailNotificationsCommand;
use App\Models\{Message, Patient};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Carbon, Redis, ResponseCode;


class UnreadMessageEmailNotificationsTest extends TestCase
{
    use DatabaseMigrations;

    protected function getMessageEmailCommandOutput()
    {
        return $this->getCommandOutput('messages:email');
    }

    public function test_it_finds_unread_messages_in_the_last_15_minutes_if_no_redis_key()
    {
        Redis::del(SendUnreadMessageEmailNotificationsCommand::LAST_PROCESSED_ID_REDIS_KEY);

        foreach ([-30, -20, -15, -10, 5, 5, 10] as $minutesFromNow) {
            factory(Message::class)->create([
                'created_at' => Carbon::parse("{$minutesFromNow} minutes"),
                'read_at' => null,
            ]);
        }

        factory(Message::class)->create([
            'created_at' => Carbon::parse("-5 minutes"),
            'read_at' => Carbon::now(),
        ]);

        $output = $this->getMessageEmailCommandOutput();

        $this->assertEquals('Looking for unread messages.', $output[0]);
        $this->assertEquals('Last processed ID not found.', $output[1]);
        $this->assertEquals('Done. [4 emails sent.]', $output[3]);
    }

    public function test_it_continues_with_the_last_processed_message_id()
    {
        Redis::del(SendUnreadMessageEmailNotificationsCommand::LAST_PROCESSED_ID_REDIS_KEY);

        foreach ([-30, -20, -15, -10, 5, 5, 10] as $minutesFromNow) {
            factory(Message::class)->create([
                'created_at' => Carbon::parse("{$minutesFromNow} minutes"),
                'read_at' => null,
            ]);
        }

        $output = $this->getMessageEmailCommandOutput();

        foreach ([-16, -15, -10, 5, 5, 10] as $minutesFromNow) {
            factory(Message::class)->create([
                'created_at' => Carbon::parse("{$minutesFromNow} minutes"),
                'read_at' => null,
            ]);
        }

        $output = $this->getMessageEmailCommandOutput();

        $this->assertEquals('Last processed ID = 7.', $output[1]);
        $this->assertEquals('Done. [6 emails sent.]', $output[2]);
    }

    public function test_email_is_sent()
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
}
