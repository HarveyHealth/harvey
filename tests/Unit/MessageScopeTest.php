<?php

namespace Tests\Unit;

use App\Models\{Patient, Message};
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MessageScopeTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_only_returns_messages_with_enabled_senders()
    {
        $patient = factory(Patient::class)->create();

        factory(Message::class, 2)->create();

        $appointment = factory(Message::class)->create([
            'sender_user_id' => $patient->user->id
        ]);

        $this->assertCount(3, Message::all());

        $patient->user->enabled = false;
        $patient->user->save();

        $this->assertCount(2, Message::all());
    }

    public function test_it_only_returns_messages_with_enabled_recipients()
    {
        $patient = factory(Patient::class)->create();

        factory(Message::class, 2)->create();

        $appointment = factory(Message::class)->create([
            'recipient_user_id' => $patient->user->id
        ]);

        $this->assertCount(3, Message::all());

        $patient->user->enabled = false;
        $patient->user->save();

        $this->assertCount(2, Message::all());
    }
}
