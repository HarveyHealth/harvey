<?php

<<<<<<< HEAD
use App\Models\LabOrder;
use App\Models\LabTest;
=======
use App\Models\{LabOrder, LabTest, SKU};
>>>>>>> origin/lab-orders-tests-be
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
<<<<<<< HEAD
        $labTest = factory(LabTest::class, 5)->create(['lab_order_id' => $labOrder->id]);
=======

        $skus = factory(SKU::class, 3)->create([
            'item_type' => 'product',
            'name' => collect(['Micronutrient', 'Adrenals', 'Toxic Metals', 'Hormones'])->random()
        ]);

        $labTest = factory(LabTest::class, 5)->create([
            'lab_order_id' => $labOrder->id,
            'sku_id' => $skus->random()->id
        ]);
>>>>>>> origin/lab-orders-tests-be
    }
}



