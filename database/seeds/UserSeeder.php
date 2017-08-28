<?php

use App\Models\{Admin, License, Patient, Practitioner, PractitionerSchedule, User};
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Admin::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'admin@goharvey.com',
                'phone' => '3101234567'
            ])->id
        ]);

        factory(Patient::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'patient@goharvey.com',
                'phone' => '3101234568',
                'state' => 'CA',
                'zip' => 90401,
            ])->id
        ]);

        factory(Practitioner::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'practitioner@goharvey.com',
                'phone' => '3101234569',
            ])->id
        ])->each(function ($practitioner) {
            $practitioner->schedule()->save(factory(PractitionerSchedule::class)->make());
            $practitioner->licenses()->save(factory(License::class)->make([
                'state' => 'CA',
                ])
            );
        });
    }
}
