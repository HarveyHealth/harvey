<?php

namespace App\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\TransactionalEmail;
use App\Events\LabOrderRecommended;

class SendPatientLabOrderCreatedEmail implements ShouldQueue
{
    public function handle(LabOrderRecommended $event)
    {
        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($event->lab_order->patient->user->email)
            ->setTemplate('patient.lab_order.recommended')
            ->setTemplateModel([
                'sender_name' => $event->lab_order->practitioner->user->full_name,
                'labs_link' => config('app.url') . '/dashboard#/lab_orders',
            ]);

        dispatch($transactionalEmailJob);
    }
}
