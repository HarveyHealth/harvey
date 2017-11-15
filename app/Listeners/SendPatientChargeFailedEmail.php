<?php

namespace App\Listeners;

use App\Events\ChargeFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Lib\TransactionalEmail;

class SendPatientChargeFailedEmail implements ShouldQueue
{
    public function handle(ChargeFailed $event)
    {
        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($event->invoice->patient->user->email)
            ->setTemplate('patient.charge.failed')
            ->setTemplateModel([
                'amount' => $event->invoice->amount,
                'card_brand' => $event->invoice->card_brand,
                'card_last_four' => $event->invoice->card_last_four,
            ]);

        dispatch($transactionalEmailJob);
    }
}
