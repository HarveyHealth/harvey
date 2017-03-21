<?php

use Illuminate\Database\Seeder;
use App\Models\PractitionerType;

class PractitionerTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(PractitionerType::class)->create([
            'name' => 'Naturopathic Doctor',
            'rate' => 300
        ]);
    }
}
