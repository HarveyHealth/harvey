<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class LogSentMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageSending  $event
     * @return void
     */
    public function handle(MessageSending $event)
    {
    	$recipientCollection = collect(array_keys($event->message->getTo()));
    	$recipientCollection->each(function($recipient) use ($event) {
    		$subject = $event->message->getSubject();
    		$message = $event->message->toString();
    		Log::info($this->logString($recipient, $subject, $message));
    	});
    }

    /**
     * Generate the log output after sending an email
     * @param  String $recipient
     * @param  String $subject
     * @param  String $message
     * @return String
     */
    public function logString($recipient, $subject, $message) {
    	return "Email sent to {$recipient} with subject \"{$subject}\" \n\"{$message}\"";
    }
}
