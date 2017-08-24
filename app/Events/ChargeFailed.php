<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Invoice;

class ChargeFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invoice;
    public $transaction;
    public $exception;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, $exception = null, $transaction = null)
    {
        $this->invoice = $invoice;
        $this->exception = $exception;
        $this->transaction = $transaction;
    }
}
