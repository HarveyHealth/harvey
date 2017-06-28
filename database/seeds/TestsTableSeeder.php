<?php

use Illuminate\Database\Seeder;
use App\Models\{Patient, Practitioner, SKU};

class TestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Test::class, 3)->create([
            'patient_id' => Patient::first()->id,
            'practitioner_id' => Practitioner::first()->id,
            'sku_id' => SKU::all()->random()->id,
        ]);

        factory(App\Models\Test::class, 3)->create([
            'sku_id' => SKU::all()->random()->id,
        ]);
    }
}
