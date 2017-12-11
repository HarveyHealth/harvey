<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Message;
use App\Models\Patient;
use App\Models\Practitioner;
use App\Events\MessageCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon;
use ResponseCode;

class MessageTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_allows_a_patient_to_send_a_new_message()
    {
        $patient = factory(Patient::class)->create();
        $practitioner = factory(Practitioner::class)->create();
        $message = Faker::create()->text;

        $parameters = [
            'recipient_user_id' => $practitioner->user->id,
            'message' => $message,
            'subject' => 'Hello',
        ];

        Passport::actingAs($patient->user);
        $response = $this->json('POST', 'api/v1/messages', $parameters);

        $response->assertStatus(ResponseCode::HTTP_CREATED);

        $response->assertJsonFragment(['sender_user_id' => "{$patient->user->id}"]);
        $response->assertJsonFragment(['recipient_user_id' => "{$practitioner->user->id}"]);

        $this->assertDatabaseHas('messages', ['message' => $message]);
    }

    public function test_a_new_message_only_requires_a_recipient()
    {
        $patient = factory(Patient::class)->create();
        $practitioner = factory(Practitioner::class)->create();

        $subject = Faker::create()->text;

        $parameters = [
            'recipient_user_id' => $practitioner->user->id,
        ];

        Passport::actingAs($patient->user);
        $response = $this->json('POST', 'api/v1/messages', $parameters);

        $response->assertStatus(ResponseCode::HTTP_CREATED);

        $response->assertJsonFragment(['sender_user_id' => "{$patient->user->id}"]);
        $response->assertJsonFragment(['recipient_user_id' => "{$practitioner->user->id}"]);

        $this->assertDatabaseHas('messages', ['recipient_user_id' => $practitioner->user->id]);
    }

    public function test_it_allows_a_patient_to_retrieve_his_messages()
    {
        $patient = factory(Patient::class)->create();

        factory(Message::class, 3)->create(['recipient_user_id' => $patient->user->id]);
        factory(Message::class, 3)->create(['sender_user_id' => $patient->user->id]);

        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/messages');

        $response->assertStatus(ResponseCode::HTTP_OK);
        $this->assertCount(6, $response->original['data']);
    }

    public function test_it_allows_a_patient_to_retrieve_a_message_to_him()
    {
        $patient = factory(Patient::class)->create();
        $message = factory(Message::class)->create(['recipient_user_id' => $patient->user->id]);

        Passport::actingAs($patient->user);
        $response = $this->json('GET', "api/v1/messages/{$message->id}");

        $response->assertStatus(ResponseCode::HTTP_OK);
        $response->assertJsonFragment(['message' => $message->message]);
    }

    public function test_it_allows_a_patient_to_retrieve_a_message_from_him()
    {
        $patient = factory(Patient::class)->create();
        $message = factory(Message::class)->create(['sender_user_id' => $patient->user->id]);

        Passport::actingAs($patient->user);
        $response = $this->json('GET', "api/v1/messages/{$message->id}");

        $response->assertStatus(ResponseCode::HTTP_OK);
        $response->assertJsonFragment(['message' => $message->message]);
    }

    public function test_it_does_not_allows_a_patient_to_retrieve_others_messages()
    {
        $patient = factory(Patient::class)->create();
        $message = factory(Message::class)->create(['recipient_user_id' => $patient->user->id]);

        Passport::actingAs(factory(Patient::class)->create()->user);
        $response = $this->json('GET', "api/v1/messages/{$message->id}");

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_allows_an_admin_to_retrieve_messages_for_other_users()
    {
        $patient = factory(Patient::class)->create();

        $message = factory(Message::class, 3)->create(['recipient_user_id' => $patient->user->id]);
        $message = factory(Message::class, 3)->create();

        Passport::actingAs(factory(Admin::class)->create()->user);
        $response = $this->json('GET', "api/v1/messages/?recipient_user_id={$patient->user->id}");

        $response->assertStatus(ResponseCode::HTTP_OK);
        $this->assertCount(3, $response->original['data']);
    }

    public function test_it_allows_an_admin_to_retrieve_a_message_created_by_other_user()
    {
        $patient = factory(Patient::class)->create();

        $message = factory(Message::class)->create(['recipient_user_id' => $patient->user->id]);

        Passport::actingAs(factory(Admin::class)->create()->user);
        $response = $this->json('GET', "api/v1/messages/{$message->id}");

        $response->assertStatus(ResponseCode::HTTP_OK);
        $response->assertJsonFragment(['message' => $message->message]);
    }

    public function test_it_allows_to_filter_by_unread()
    {
        $patient = factory(Patient::class)->create();

        factory(Message::class, 3)->create([
            'recipient_user_id' => $patient->user->id,
            'read_at' => Carbon::now(),
        ]);

        factory(Message::class, 5)->create([
            'recipient_user_id' => $patient->user->id,
            'read_at' => null,
        ]);

        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/messages?filter=unread');

        $response->assertStatus(ResponseCode::HTTP_OK);
        $this->assertCount(5, $response->original['data']);
    }

    public function test_it_allows_to_filter_by_sender_id()
    {
        $patient = factory(Patient::class)->create();
        $senderPatient = factory(Patient::class)->create();

        factory(Message::class, 2)->create([
            'recipient_user_id' => $patient->user->id,
        ]);

        factory(Message::class, 4)->create([
            'recipient_user_id' => $patient->user->id,
            'sender_user_id' => $senderPatient->user->id,
        ]);

        Passport::actingAs($patient->user);
        $response = $this->json('GET', "api/v1/messages?&sender_user_id={$senderPatient->user->id}");

        $response->assertStatus(ResponseCode::HTTP_OK);
        $this->assertCount(4, $response->original['data']);
    }

    public function test_it_allows_to_filter_by_term()
    {
        $this->markTestSkipped('Skipped due to Algolia concurrency issues (shared indexes).');

        $patient = factory(Patient::class)->create();

        factory(Message::class, 4)->create([
            'recipient_user_id' => $patient->user->id,
            'message' => 'A message for testing...',
            'read_at' => null,
        ]);

        factory(Message::class, 3)->create([
            'recipient_user_id' => $patient->user->id,
            'message' => 'Some other message.',
            'read_at' => Carbon::now(),
        ]);

        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/messages?term=test');

        $response->assertStatus(ResponseCode::HTTP_OK);
        $this->assertCount(4, $response->original['data']);
    }

    public function test_it_allows_to_filter_by_term_and_sender_id()
    {
        $this->markTestSkipped('Skipped due to Algolia concurrency issues (shared indexes).');

        $patient = factory(Patient::class)->create();
        $senderPatient = factory(Patient::class)->create();

        factory(Message::class, 2)->create([
            'recipient_user_id' => $patient->user->id,
            'message' => 'A message for testing...',
            'read_at' => null,
        ]);

        factory(Message::class, 3)->create([
            'recipient_user_id' => $patient->user->id,
            'message' => 'A message for testing...',
            'sender_user_id' => $senderPatient->user->id,
            'read_at' => null,
        ]);

        factory(Message::class, 4)->create([
            'recipient_user_id' => $patient->user->id,
            'message' => 'Some other message.',
            'read_at' => Carbon::now(),
        ]);

        Passport::actingAs($patient->user);
        $response = $this->json('GET', "api/v1/messages?term=test&sender_user_id={$senderPatient->user->id}");

        $response->assertStatus(ResponseCode::HTTP_OK);
        $this->assertCount(3, $response->original['data']);
    }

    public function test_it_allows_to_mark_a_message_as_read()
    {
        $patient = factory(Patient::class)->create();

        $message = factory(Message::class)->create([
            'recipient_user_id' => $patient->user->id,
            'read_at' => null,
        ]);

        Passport::actingAs($patient->user);
        $response = $this->json('PUT', 'api/v1/messages/1/read');
        $response->assertStatus(ResponseCode::HTTP_OK);
        $this->assertNotNull($response->original['data']['attributes']['read_at']);

        $response = $this->json('GET', 'api/v1/messages/1');
        $response->assertStatus(ResponseCode::HTTP_OK);

        $this->assertNotNull($response->original['data']['attributes']['read_at']);
    }

    public function test_it_only_the_recipient_can_mark_a_message_as_read()
    {
        $patient = factory(Patient::class)->create();

        $message = factory(Message::class)->create([
            'recipient_user_id' => $patient->user->id,
            'read_at' => null,
        ]);

        Passport::actingAs(factory(Patient::class)->create()->user);
        $response = $this->json('PUT', 'api/v1/messages/1/read');
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_does_not_allows_an_user_to_send_more_than_50_messages_in_a_short_period_of_time()
    {
        $patient = factory(Patient::class)->create();

        $parameters = [
            'recipient_user_id' => factory(Practitioner::class)->create()->user->id,
            'message' => 'Hi there!',
            'subject' => 'Hello',
        ];

        for ($i = 0; $i <= 50; $i++) {
            Passport::actingAs($patient->user);
            $response = $this->json('POST', 'api/v1/messages', $parameters);
            $response->assertStatus(ResponseCode::HTTP_CREATED);
        }

        Passport::actingAs($patient->user);
        $response = $this->json('POST', 'api/v1/messages', $parameters);
        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_allows_an_admin_to_delete_message()
    {
        $message = factory(Message::class)->create();

        Passport::actingAs(factory(Admin::class)->create()->user);
        $response = $this->json('DELETE', "api/v1/messages/{$message->id}");

        $response->assertStatus(ResponseCode::HTTP_NO_CONTENT);
    }

    public function test_it_does_not_allows_a_recipient_to_delete_a_message()
    {
        $practitioner = factory(Practitioner::class)->create();
        $message = factory(Message::class)->create(['recipient_user_id' => $practitioner->user->id]);

        Passport::actingAs($practitioner->user);
        $response = $this->json('DELETE', "api/v1/messages/{$message->id}");

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_does_not_allows_a_sender_to_delete_a_message()
    {
        $practitioner = factory(Practitioner::class)->create();
        $message = factory(Message::class)->create(['sender_user_id' => $practitioner->user->id]);

        Passport::actingAs($practitioner->user);
        $response = $this->json('DELETE', "api/v1/messages/{$message->id}");

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function test_it_returns_10_days_before_last_message()
    {
        $patient = factory(Patient::class)->create();

        // creates some old Messages
        factory(Message::class)->create([
            'recipient_user_id' => $patient->user->id,
            'created_at' => \Carbon::parse('-20 days')
        ]);
        factory(Message::class)->create([
            'recipient_user_id' => $patient->user->id,
            'created_at' => \Carbon::parse('-15 days')
        ]);
        factory(Message::class)->create([
            'sender_user_id' => $patient->user->id,
            'created_at' => \Carbon::parse('-10 days')
        ]);

        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/messages');

        $response->assertStatus(ResponseCode::HTTP_OK);
        $this->assertCount(2, $response->original['data']);
    }

    public function test_it_returns_10_days_before_unread()
    {
        $patient = factory(Patient::class)->create();

        // creates some old Messages
        factory(Message::class)->create([
            'recipient_user_id' => $patient->user->id,
            'read_at'=> \Carbon::parse('-20 days'),
            'created_at' => \Carbon::parse('-20 days')
        ]);

        factory(Message::class)->create([
            'recipient_user_id' => $patient->user->id,
            'read_at' =>  null,
            'created_at' => \Carbon::parse('-15 days')
        ]);


        factory(Message::class, 3)->create([
            'sender_user_id' => $patient->user->id,
            'read_at' => null,
        ]);

        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/messages');

        $response->assertStatus(ResponseCode::HTTP_OK);
        $this->assertCount(5, $response->original['data']);
    }
}
