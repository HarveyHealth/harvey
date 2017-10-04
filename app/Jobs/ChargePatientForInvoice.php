<?php

namespace App\Jobs;

use App\Lib\Cashier;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ChargePatientForInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $invoice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return Cashier::chargePatientForInvoice($this->invoice);
    }
}
