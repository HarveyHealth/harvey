<?php

use Illuminate\Database\Seeder;
use App\Models\Condition;

class ConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Condition::class, 10)->create();
    }
}
