<?php

namespace App\Events;

use App\Models\{Invoice, Transaction};
use Illuminate\Foundation\Events\Dispatchable;
use Exception;

class ChargeFailed
{
    use Dispatchable;

    public $invoice;
    public $transaction;
    public $exception;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, Exception $exception = null, Transaction $transaction = null)
    {
        $this->invoice = $invoice;
        $this->exception = $exception;
        $this->transaction = $transaction;
    }
}
