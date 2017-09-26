<?php

use Illuminate\Database\Seeder;
use App\Models\{Patient, Practitioner, SKU, Test};

class TestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Test::class, 3)->create([
            'patient_id' => Patient::first()->id,
            'practitioner_id' => Practitioner::first()->id,
            'sku_id' => SKU::all()->random()->id,
        ]);

        factory(Test::class, 3)->create([
            'sku_id' => SKU::all()->random()->id,
        ]);
    }
}
