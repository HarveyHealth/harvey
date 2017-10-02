<?php

namespace App\Listeners;

use App\Events\LabOrderConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\TransactionalEmail;

class SendPatientLabOrderConfirmedEmail implements ShouldQueue
{
    public function handle(LabOrderConfirmed $event)
    {
        $invoice = Cashier::getOrCreateInvoice($event->lab_order);
        $charges = [];

        foreach ($invoice->items as $item) {
            $charges[] = [
                'laboratory' => ->sku->labTestInformation->lab_name,
                'name' => $item->description,
                'price' => $item->amount,
            ];
        }

        $transactionalEmailJob = TransactionalEmail::createJob()
        ->setTo($event->lab_order->patient->user->email)
        ->setTemplate('patient.lab_order.complete')
        ->setTemplateModel([
            'address' => "{$event->lab_order->addres_1} {$event->lab_order->addres_2}",
            'charges' => $charges,
            'discount' => $invoice->discount,
            'doctor' => $event->lab_order->practitioner->user->full_name,
            'subtotal' => $invoice->subtotal,
            'total' => $invoice->total,
        ]);

        dispatch($transactionalEmailJob);
    }
}
