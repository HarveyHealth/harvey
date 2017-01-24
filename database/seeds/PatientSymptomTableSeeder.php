<?php

use Illuminate\Database\Seeder;

class PatientSymptomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $patient = App\Models\User::whereUserType('patient')->first();
        $symptoms = App\Models\Symptom::limit(3)->get();
        foreach ($symptoms as $symptom) {
            factory(App\Models\PatientSymptom::class)->create(
                [
                    'patient_user_id' => $patient->id,
                    'symptom_id' => $symptom->id,
                ]
            );
        }
    }
}
