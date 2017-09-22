<?php

use App\Models\{LabOrder, LabTest, SKU};
use Illuminate\Database\Seeder;

class LabOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $labOrder = factory(LabOrder::class)->create();

        for ($i=0; $i < 5; $i++) {
            $labTest = factory(LabTest::class)->create([
                'lab_order_id' => $labOrder->id,
                'sku_id' => SKU::where('item_type', 'lab-test')->get()->random()->id
            ]);
        }
    }
}



