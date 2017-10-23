<?php

use Illuminate\Database\Seeder;
use App\Models\DiscountCode;

class DiscountCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(DiscountCode::class, 3)->create();
    }
}
