<?php
namespace Seeder;
use Illuminate\Database\Seeder;

class InitialDatabaseSeeder extends Seeder
{
    public function run()
    {
        // In order for the production database to function properly, We need to
        // have at least a DO and an ND with some scheduled availability.
        // We will also need to have an Oauth Client for the Vue app to consume
        // The API.
        $pt1 = \App\Models\PractitionerType::create([
            'enabled' => true,
            'name' => 'Naturopathic Doctor',
            'rate' => 150.00
        ]);

        $nd = \App\Models\User::create([
            'first_name' => 'Amanda',
            'last_name' => 'Frick',
            'email' =>  'test+amandafrick@goharvey.com',
            'phone' => '3101231234',
            'password' => bcrypt('secret'),
            'timezone' => 'America/Los_Angeles'
        ]);

        $practitioner1 = \App\Models\Practitioner::create([
            'enabled' => true,
            'practitioner_type' => $pt1->id,
            'user_id' => $nd->id
        ]);

        \App\Models\PractitionerSchedule::create([
            'practitioner_id' => $practitioner1->id,
            'day_of_week' => 'Wednesday',
            'start_time' => '08:00:00',
            'stop_time' => '10:00:00'
        ]);

        $pt2 = \App\Models\PractitionerType::create([
            'enabled' => true,
            'name' => 'Doctor of Osteopathy',
            'rate' => 300.00
        ]);

        $do = \App\Models\User::create([
            'first_name' => 'Rachel',
            'last_name' => 'West',
            'email' =>  'test+rachelwest@goharvey.com',
            'phone' => '3101231235',
            'password' => bcrypt('secret'),
            'timezone' => 'America/Los_Angeles'
        ]);

        $practitioner2 = \App\Models\Practitioner::create([
            'enabled' => true,
            'practitioner_type' => $pt2->id,
            'user_id' => $do->id
        ]);

        \App\Models\PractitionerSchedule::create([
            'practitioner_id' => $practitioner2->id,
            'day_of_week' => 'Monday',
            'start_time' => '11:00:00',
            'stop_time' => '15:00:00'
        ]);

        factory(Laravel\Passport\Client::class)->create([
            'name' => 'Harvey-Vue',
            'secret' => 'Uew3kusLxnzNT5t4EcjxMM4Qso4XJDJbEO7tYyCu',
            'redirect' => config('app.url')
        ]);
    }
}
