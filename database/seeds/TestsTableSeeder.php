<?php

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Practitioner;

class TestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create some records with specific users
        factory(App\Models\Test::class, 3)->create([
            'patient_id' => Patient::first()->id,
            'practitioner_id' => Practitioner::first()->id
        ]);

        // then create some random ones
        factory(App\Models\Test::class, 3)->create();
    }
}
