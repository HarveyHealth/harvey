<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use App\Models\Practitioner;
use App\Models\Appointment;
use App\Models\PatientNote;

class AppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $patient = Patient::first();
        $practitioner = Practitioner::first();

        // Create 5 past appointments for our test patient and practitioner
        factory(Appointment::class, 5)->states('past')->create([
            'patient_id' => $patient->id,
            'practitioner_id' => $practitioner->id
        ])->each(function ($appointment) use ($patient, $practitioner) {
            $appointment->notes()->save(factory(PatientNote::class)->make([
                'patient_id' => $patient->id,
                'practitioner_id' => $practitioner->id
            ]));
        });

        // Create 2 upcoming appointments for our test patient and practitioner
        factory(Appointment::class, 2)->create([
            'patient_id' => $patient->id,
            'practitioner_id' => $practitioner->id
        ]);

        // then create some random ones
        factory(Appointment::class, 10)->create();
    }
}
