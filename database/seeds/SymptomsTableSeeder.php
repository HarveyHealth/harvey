<?php

use Illuminate\Database\Seeder;
use App\Models\Symptom;

class SymptomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Symptom::class, 10)->create();
    }
}
