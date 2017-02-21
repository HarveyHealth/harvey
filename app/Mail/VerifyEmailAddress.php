<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Interfaces\Mailable as MailableInterface;

class VerifyEmailAddress extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mailable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MailableInterface $mailable)
    {
        $this->mailable = $mailable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->markdown('mail.verify_email');
    }
}
