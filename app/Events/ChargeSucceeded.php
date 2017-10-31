<?php

namespace App\Events;

use App\Models\{Invoice, Transaction};
use Illuminate\Foundation\Events\Dispatchable;

class ChargeSucceeded
{
    use Dispatchable;

    public $invoice;
    public $transaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, Transaction $transaction)
    {
        $this->invoice = $invoice;
        $this->transaction = $transaction;
    }
}
