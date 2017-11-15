<?php

namespace App\Listeners;

use App\Events\LabTestProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\TransactionalEmail;

class SendPatientLabTestProcessingEmail implements ShouldQueue
{
    public function handle(LabTestProcessing $event)
    {
        $transactional_email_job = TransactionalEmail::createJob()
            ->setTo($event->lab_test->labOrder->patient->user->email)
            ->setTemplate('patient.lab_test.processing')
            ->setTemplateModel([
                'lab_test_name' => $event->lab_test->sku->name,
                'lab_name' =>   $event->lab_test->information->lab_name,
            ]);

        dispatch($transactional_email_job);
    }
}
