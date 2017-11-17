<?php

use Illuminate\Database\Seeder;
use App\Models\SoapNote;

class SoapNotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(SoapNote::class, 3)->create();
    }
}
