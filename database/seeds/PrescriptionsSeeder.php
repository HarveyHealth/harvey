<?php

use Illuminate\Database\Seeder;
use App\Models\Prescription;

class PrescriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Prescription::class, 3)->create();
    }
}
