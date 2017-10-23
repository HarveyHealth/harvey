<?php

namespace App\Events;

use App\Models\{Invoice, Transaction};
use Illuminate\Foundation\Events\Dispatchable;

class ChargeFailed
{
    use Dispatchable;

    public $invoice;
    public $message;
    public $transaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, string $message = null, Transaction $transaction = null)
    {
        $this->invoice = $invoice;
        $this->message = $message;
        $this->transaction = $transaction;
    }
}
