<?php

namespace App\Listeners;

use App\Events\LabTestResultCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Lib\TransactionalEmail;

class SendPractitionerLabResultCreatedEmail implements ShouldQueue
{
    public function handle(LabTestResultCreated $event)
    {
        $lab_test_result = $event->lab_test_result;
        $to = $lab_test_result->labTest->labOrder->practitioner->user->email;
        $patient_name = $lab_test_result->labTest->labOrder->patient->user->full_name;
        $lab_test_name = $lab_test_result->labTest->sku->name;
        
        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($to)
            ->setTemplate('practitioner.lab_test_result.created')
            ->setTemplateModel([
                'lab_test_name' => $lab_test_name,
                'patient_name' => $patient_name,
            ]);

        dispatch($transactionalEmailJob);
    }
}
