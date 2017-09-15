<?php

use App\Models\Message;
use App\Models\Patient;
use App\Models\Practitioner;
use Illuminate\Database\Seeder;

class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Message::class, 6)->create();

        $patient = factory(Patient::class)->create();
        $practitioner = factory(Practitioner::class)->create();

        factory(Message::class)->create([
            'subject' => 'Subject one.',
            'recipient_user_id' => $practitioner->user->id,
            'sender_user_id' => $patient->user->id,
        ]);

        factory(Message::class)->create([
            'subject' => 'Subject one.',
            'recipient_user_id' => $patient->user->id,
            'sender_user_id' => $practitioner->user->id,
        ]);

        factory(Message::class)->create([
            'subject' => 'Subject one.',
            'recipient_user_id' => $practitioner->user->id,
            'sender_user_id' => $patient->user->id,
        ]);

        factory(Message::class)->create([
            'subject' => 'Subject two.',
            'recipient_user_id' => $practitioner->user->id,
            'sender_user_id' => $patient->user->id,
        ]);

        factory(Message::class)->create([
            'subject' => 'Subject two.',
            'recipient_user_id' => $patient->user->id,
            'sender_user_id' => $practitioner->user->id,
        ]);

        factory(Message::class)->create([
            'subject' => 'Subject two.',
            'recipient_user_id' => $practitioner->user->id,
            'sender_user_id' => $patient->user->id,
        ]);
    }
}
