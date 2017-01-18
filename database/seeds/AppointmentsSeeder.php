<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class AppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $patient = User::where('user_type', 'patient')->first();
        $practitioner = User::where('user_type', 'practitioner')->first();

        // Create 5 past appointments for our test patient and practitioner
        factory(App\Models\Appointment::class, 5)->states('past')->create([
            'patient_user_id' => $patient->id,
            'practitioner_user_id' => $practitioner->id
        ])->each(function ($appointment) use ($patient, $practitioner) {
            $appointment->notes()->save(factory(App\Models\PatientNote::class)->make([
                'patient_user_id' => $patient->id,
                'practitioner_user_id' => $practitioner->id
            ]));
        });

        // Create 2 upcoming appointments for our test patient and practitioner
        factory(App\Models\Appointment::class, 2)->create([
            'patient_user_id' => $patient->id,
            'practitioner_user_id' => $practitioner->id
        ]);

        // then create some random ones
        factory(App\Models\Appointment::class, 10)->create();
    }
}
