<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class TestsTableSeeder extends Seeder
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

        // create some records with specific users
        factory(App\Models\Test::class, 10)->create([
            'patient_user_id' => $patient->id,
            'practitioner_user_id' => $practitioner->id
        ]);

        // then create some random ones
        factory(App\Models\Test::class, 10)->create();
    }
}
