<?php

namespace App\Listeners;

use App\Events\LabTestReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\TransactionalEmail;

class SendPatientLabTestReceivedEmail implements ShouldQueue
{
    public function handle(LabTestReceived $event)
    {
        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($event->labTest->labOrder->patient->user->email)
            ->setTemplate('patient.lab_test.received')
            ->setTemplateModel([
                'lab_test_name' => $event->labTest->sku->name,
                'lab_name' =>   $event->labTest->information->lab_name,
            ]);

        dispatch($transactionalEmailJob);
    }
}
