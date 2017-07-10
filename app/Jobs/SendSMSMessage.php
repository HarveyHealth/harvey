<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\{SerializesModels, InteractsWithQueue};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Lib\SMS;

class SendSMSMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $number;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($number, string $message)
    {
        $this->number = $number;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return \App::make(SMS::class)->sendMessageToNumber($this->number, $this->message);
    }
}
