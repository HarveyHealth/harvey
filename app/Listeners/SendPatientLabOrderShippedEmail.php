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

        foreach ($event->labOrder->labTests()->shipped()->get() as $labTest) {
            $labTests[] = [
                'name' => $labTest->sku->name ?? '',
                'lab_name' => $labTest->information->lab_name ?? '',
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
