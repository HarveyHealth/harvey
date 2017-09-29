<?php

namespace App\Listeners;

use App\Events\LabOrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\TransactionalEmail;

class SendPatientLabOrderShippedEmail implements ShouldQueue
{
    public function handle(LabOrderShipped $event)
    {
        $labTests = [];

        // Include names of labs ordered and lab, ie "Spectracell Micronutrient" (maximum 3)
        foreach ($event->labOrder->labTests()->leftJoin('skus','skus.id','=','sku_id')
                ->orderBy('skus.name')->limit(3)->get() as $labTest) {
            $labTests[] = [
                'name' => $labTest->sku->name,
                'lab_name' => $labTest->information->lab_name,
            ];
        }

        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($event->labOrder->patient->user->email)
            ->setTemplate('patient.lab_order.shipped')
            ->setTemplateModel([
                'tracking_number' => $event->labOrder->shipment_code,
                'lab_tests' => $labTests,
            ]);

        dispatch($transactionalEmailJob);
    }
}
