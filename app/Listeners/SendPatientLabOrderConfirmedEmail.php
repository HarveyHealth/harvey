<?php

namespace App\Listeners;

use App\Events\LabOrderConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\{Cashier, TransactionalEmail};

class SendPatientLabOrderConfirmedEmail implements ShouldQueue
{
    public function handle(LabOrderConfirmed $event)
    {
        $invoice = $event->appointment->invoice;

        if (!$invoice) {
            ops_error('SendPatientLabOrderConfirmedEmail error!', "No invoice exists for Lab Order #{$invoice->id}. No email sent.");
            return;
        }

        $charges = [];

        foreach ($invoice->items as $item) {
            $charges[] = [
                'laboratory' => $item->sku->labTestInformation->lab_name ?? '',
                'name' => $item->description,
                'price' => $item->amount,
            ];
        }

        $transactionalEmailJob = TransactionalEmail::createJob()
        ->setTo($event->lab_order->patient->user->email)
        ->setTemplate('patient.lab_order.confirmed')
        ->setTemplateModel([
            'address' => "{$event->lab_order->addres_1} {$event->lab_order->addres_2}",
            'charges' => $charges,
            'discount' => $invoice->discount,
            'doctor' => $event->lab_order->practitioner->user->full_name,
            'subtotal' => $invoice->subtotal,
            'total' => $invoice->amount,
        ]);

        dispatch($transactionalEmailJob);
    }
}
