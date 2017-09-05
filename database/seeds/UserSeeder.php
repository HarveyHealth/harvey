<?php

use Illuminate\Database\Seeder;
use App\Models\{
    Admin,
    Attachment,
    License,
    Patient,
    Practitioner,
    PractitionerSchedule,
    Prescription,
    SoapNote,
    User
};

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
                'phone' => '3101234568'
            ])->id
        ])->each(function ($patient) {
            $patient->attachments()->saveMany(factory(Attachment::class, 3)->make());
            $patient->prescriptions()->saveMany(factory(Prescription::class, 3)->make());
            $patient->soapNotes()->saveMany(factory(SoapNote::class, 3)->make());
        });

        factory(Practitioner::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'practitioner@goharvey.com',
                'phone' => '3101234569',
            ])->id
        ])->each(function ($practitioner) {
            $practitioner->schedule()->save(factory(PractitionerSchedule::class)->make());
            $practitioner->licenses()->save(factory(License::class)->make());
        });
    }
}
