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
        $labTest = factory(LabTest::class, 5)->create([
            'lab_order_id' => $labOrder->id,
            'sku_id' => SKU::all()->random()->id
        ]);
    }
}



