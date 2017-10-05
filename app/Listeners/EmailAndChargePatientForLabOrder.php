<?php

namespace App\Listeners;

use App\Events\LabOrderConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\Cashier;
use App\Jobs\ChargePatientForInvoice;
use App\Lib\TransactionalEmail;

class EmailAndChargePatientForLabOrder implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  LabOrderConfirmed  $event
     * @return void
     */
    public function handle(LabOrderConfirmed $event)
    {
        $lab_order = $event->lab_order;

        if ($event->lab_order->invoice_id && $event->lab_order->invoice->isNotOutstanding()) {
            return false;
        }

        $invoice = Cashier::getOrCreateInvoice($event->lab_order);

        if (!$event->lab_order->patient->user->hasACard()) {
            ops_warning('ChargePatientForLabOrder warning!' , "User ID #{$event->lab_order->patient->user->id} doesn't have a credit card associated. Can't charge Invoice ID #{$invoice->id} [LabOrder ID #{$event->lab_order->id}].", 'operations');
            return false;
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
        dispatch(new ChargePatientForInvoice($invoice));
    }
}
