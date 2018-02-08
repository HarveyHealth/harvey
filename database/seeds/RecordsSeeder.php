<?php

use Illuminate\Database\Seeder;
use App\Models\{
    Attachment,
    Intake,
    LabOrder,
    LabTest,
    LabTestResult,
    Patient,
    Prescription,
    SKU,
    SoapNote
};

class RecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $patient = Patient::first() ?: factory(Patient::class)->create();

        factory(Attachment::class, 3)->create(['patient_id' => $patient->id]);
        factory(Intake::class)->create(['user_id' => $patient->user->id]);
        factory(Prescription::class, 3)->create(['patient_id' => $patient->id]);
        factory(SoapNote::class, 3)->create(['patient_id' => $patient->id]);

        $lab_order = factory(LabOrder::class)->create(['patient_id' => $patient->id]);

        $SKUs = SKU::where('item_type', 'lab-test')->get()->shuffle();

        foreach (range(0, 1) as $i) {
            $lab_test = factory(LabTest::class)->create([
                'lab_order_id' => $lab_order->id,
                'sku_id' => $SKUs->pop()->id,
            ]);

            factory(LabTestResult::class)->create([
                'lab_test_id' => $lab_test->id,
            ]);
        }
    }
}
