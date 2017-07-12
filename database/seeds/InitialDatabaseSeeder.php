<?php

use App\Models\User;
use App\Models\Practitioner;
use App\Models\PractitionerSchedule;
use Laravel\Passport\Client;
use Illuminate\Database\Seeder;

class InitialDatabaseSeeder extends Seeder
{
    public function run()
    {
        // In order for the production database to function properly, We need to
        // have at least a DO and an ND with some scheduled availability.
        // We will also need to have an Oauth Client for the Vue app to consume
        // The API.

        $nd = User::create([
            'first_name' => 'Amanda',
            'last_name' => 'Frick',
            'email' =>  'test+amandafrick@goharvey.com',
            'phone' => '3101231234',
            'password' => bcrypt('secret'),
            'timezone' => 'America/Los_Angeles'
        ]);

        $practitioner1 = Practitioner::create([
            'description' => 'Naturopathic Doctor',
            'rate' => 150.00,
            'user_id' => $nd->id,
        ]);

        PractitionerSchedule::create([
            'practitioner_id' => $practitioner1->id,
            'day_of_week' => 'Wednesday',
            'start_time' => '08:00:00',
            'stop_time' => '10:00:00'
        ]);

        $do = User::create([
            'first_name' => 'Rachel',
            'last_name' => 'West',
            'email' =>  'test+rachelwest@goharvey.com',
            'phone' => '3101231235',
            'password' => bcrypt('secret'),
            'timezone' => 'America/Los_Angeles'
        ]);

        $practitioner2 = Practitioner::create([
            'description' => 'Doctor of Osteopathy',
            'rate' => 300.00,
            'user_id' => $do->id,
        ]);

        PractitionerSchedule::create([
            'practitioner_id' => $practitioner2->id,
            'day_of_week' => 'Monday',
            'start_time' => '11:00:00',
            'stop_time' => '15:00:00'
        ]);

        factory(Client::class)->create([
            'name' => 'Harvey-Vue',
            'secret' => 'Uew3kusLxnzNT5t4EcjxMM4Qso4XJDJbEO7tYyCu',
            'redirect' => config('app.url')
        ]);
    }
}
