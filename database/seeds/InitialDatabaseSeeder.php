<?php

use Illuminate\Database\Seeder;
use Laravel\Passport\Client;
use App\Models\{
    License,
    Practitioner,
    PractitionerSchedule,
    PractitionerType,
    User
};

class InitialDatabaseSeeder extends Seeder
{
    public function run()
    {
        // In order for the production database to function properly, We need to
        // have at least a DO and an ND with some scheduled availability.
        // We will also need to have an Oauth Client for the Vue app to consume
        // The API.

        Practitioner::create([
            'practitioner_type' => PractitionerType::create([
                'name' => 'Naturopathic Doctor',
                'rate' => 150.00,
            ])->id,
            'user_id' => User::create([
                'first_name' => 'Amanda',
                'last_name' => 'Frick',
                'email' =>  'test+amandafrick@goharvey.com',
                'phone' => '3101231234',
                'password' => bcrypt('secret'),
                'timezone' => 'America/Los_Angeles',
            ])->id,
        ])->each(function ($practitioner) {
            $practitioner->schedule()->save(factory(PractitionerSchedule::class)->make([
                'day_of_week' => 'Wednesday',
                'start_time' => '08:00:00',
                'stop_time' => '10:00:00',
            ]));
            $practitioner->licenses()->save(factory(License::class)->make([
                'state' => 'CA',
                'title' => 'ND',
            ]));
        });

        Practitioner::create([
            'practitioner_type' => PractitionerType::create([
                'name' => 'Doctor of Osteopathy',
                'rate' => 300.00
            ])->id,
            'user_id' => User::create([
                'first_name' => 'Rachel',
                'last_name' => 'West',
                'email' =>  'test+rachelwest@goharvey.com',
                'phone' => '3101231235',
                'password' => bcrypt('secret'),
                'timezone' => 'America/Los_Angeles'
            ])->id,
        ])->each(function ($practitioner) {
            $practitioner->schedule()->save(factory(PractitionerSchedule::class)->make([
                'day_of_week' => 'Monday',
                'start_time' => '11:00:00',
                'stop_time' => '15:00:00',
            ]));
            $practitioner->licenses()->save(factory(License::class)->make([
                'state' => 'CA',
                'title' => 'DO',
            ]));
        });

        factory(Client::class)->create([
            'name' => 'Harvey-Vue',
            'secret' => 'Uew3kusLxnzNT5t4EcjxMM4Qso4XJDJbEO7tYyCu',
            'redirect' => config('app.url')
        ]);
    }
}
