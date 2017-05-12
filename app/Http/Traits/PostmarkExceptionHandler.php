<?php

namespace App\Http\Traits;

use Postmark\Models\PostmarkException;

trait PostmarkExceptionHandler
{
    public static function handlePostmarkException(PostmarkException $exception)
    {
        if (406 == $exception->postmarkApiErrorCode) {
            \Log::warning("Mailbox {$event->appointment->patient->user->email} is marked as 'Inactive' on Postmark so PatientAppointmentEmail will not be sent.");
        } else {
            $contextualData = ['message' => $exception->message, 'api_error_code' => $exception->postmarkApiErrorCode];
            \Log::error("Unable to send email to {$event->appointment->patient->user->email}.", $contextualData);
        }
    }
}
