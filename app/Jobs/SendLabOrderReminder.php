<?php

namespace App\Jobs;

use App\Models\LabOrder;
use App\Lib\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use View;

class SendLabOrderReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $lab_order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LabOrder $lab_order)
    {
        $this->lab_order = $lab_order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // check for lab_order status
        if ($this->lab_order->status == LabOrder::RECOMMENDED_STATUS_ID){
            // send email
            $transactional_email_job = TransactionalEmail::createJob()
            ->setTo($this->lab_order->patient->user->email)
            ->setTemplate('patient.lab_order.reminder')
            ->setTemplateModel([
                'lab_orders_link' => config('app.url') . '/dashboard#/lab_orders',
            ]);

            dispatch($transactional_email_job);

            // Send SMS message
            $message = View::make("sms/lab_order_reminder")->render();
            $this->lab_order->patient->user->sendText($message);
        }
    }
}
