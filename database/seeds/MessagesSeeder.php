<?php

use App\Models\Message;
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
        factory(Message::class, 3)->create(['subject' => 'Subject one.']);
        factory(Message::class, 3)->create(['subject' => 'Subject two.']);
    }
}
