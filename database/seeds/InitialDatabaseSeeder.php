<?php

use Illuminate\Database\Seeder;
use Laravel\Passport\Client;
use App\Models\{
    Admin,
    License,
    Patient,
    Practitioner,
    PractitionerSchedule,
    PractitionerType,
    User
};

class InitialDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Practitioners

        $practitioner = Practitioner::create([
            'practitioner_type' => PractitionerType::create([
                'name' => 'Naturopathic Doctor',
                'rate' => 150.00,
            ])->id,
            'user_id' => User::create([
                'email' =>  'test+amandafrick@goharvey.com',
                'first_name' => 'Amanda',
                'last_name' => 'Frick',
                'password' => bcrypt('secret'),
                'phone' => '3101231234',
                'timezone' => 'America/Los_Angeles',
                'zip' => '90401',
            ])->id,
        ]);

        factory(PractitionerSchedule::class)->create([
            'day_of_week' => 'Wednesday',
            'practitioner_id' => $practitioner->id,
            'start_time' => '08:00:00',
            'stop_time' => '10:00:00',
        ]);

        factory(License::class)->create([
            'state' => 'CA',
            'title' => 'ND',
            'user_id' => $practitioner->user->id,
        ]);

        $practitioner = Practitioner::create([
            'practitioner_type' => PractitionerType::create([
                'name' => 'Doctor of Osteopathy',
                'rate' => 300.00
            ])->id,
            'user_id' => User::create([
                'email' =>  'test+rachelwest@goharvey.com',
                'first_name' => 'Rachel',
                'last_name' => 'West',
                'password' => bcrypt('secret'),
                'phone' => '3101231235',
                'timezone' => 'America/Los_Angeles',
                'zip' => '90401',
            ])->id,
        ]);

        factory(PractitionerSchedule::class)->create([
            'day_of_week' => 'Monday',
            'practitioner_id' => $practitioner->id,
            'start_time' => '11:00:00',
            'stop_time' => '15:00:00',
        ]);

        factory(License::class)->create([
            'state' => 'CA',
            'title' => 'DO',
            'user_id' => $practitioner->user->id,
        ]);

        factory(Client::class)->create([
            'name' => 'Harvey-Vue',
            'secret' => 'Uew3kusLxnzNT5t4EcjxMM4Qso4XJDJbEO7tYyCu',
            'redirect' => config('app.url')
        ]);

        // Create Admin

        factory(Admin::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'admin@goharvey.com',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => bcrypt('secret'),
                'phone' => '3101231236',
                'timezone' => 'America/Los_Angeles',
                'zip' => '90401',
            ])->id
        ]);

        // Create Patient

        factory(Patient::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'patient@goharvey.com',
                'first_name' => 'Patient',
                'last_name' => 'User',
                'password' => bcrypt('secret'),
                'phone' => '3101231237',
                'state' => 'CA',
                'timezone' => 'America/Los_Angeles',
                'zip' => '90401',
            ])->id
        ]);
    }
}
