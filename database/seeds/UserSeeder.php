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
                'phone' => '3101234568',
                'state' => 'CA',
                'zip' => 90401,
            ])->id
        ])->each(function ($patient) {
            $patient->attachments()->saveMany(factory(Attachment::class, 3)->make());
            $patient->prescriptions()->saveMany(factory(Prescription::class, 3)->make());
            $patient->soapNotes()->saveMany(factory(SoapNote::class, 3)->make());
        });

        $practitioner = factory(Practitioner::class)->create([
            'user_id' => factory(User::class)->create([
                'email' => 'practitioner@goharvey.com',
                'phone' => '3101234569',
            ])->id
        ]);

        factory(PractitionerSchedule::class)->create([
            'practitioner_id' => $practitioner->id,
        ]);

        factory(License::class)->create([
                'state' => 'CA',
                'user_id' => $practitioner->user_id,
        ]);
    }
}
