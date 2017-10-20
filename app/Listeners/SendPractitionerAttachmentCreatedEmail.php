<?php

namespace App\Listeners;

use App\Events\AttachmentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Lib\TransactionalEmail;

class SendPractitionerAttachmentCreatedEmail implements ShouldQueue
{
    public function handle(AttachmentCreated $event)
    {
        $attachment = $event->attachment;
        $user = $attachment->patient->user;

        if (empty($to = $user->getLastPractitioner()->email)) {
            $message = "Patient {$user->truncated_name} has uploaded an attachment but doesn't have a Practitioner assigned. Practitioner email notification skipped.";
            ops_warning('SendPractitionerAttachmentCreatedEmail', $message, 'practitioners');
            return false;
        }

        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($to)
            ->setTemplate('practitioner.attachment.created')
            ->setTemplateModel([
                'patient_name' => $user->full_name,
                'attachment_upload_date' => $attachment->created_at->format('l F j'),
                'appointment_upload_time' => $attachment->created_at->format('h:i A'),
                'patient_first_name' => $user->first_name,
                'attachment_name' => $attachment->name,
                'attachment_link' => config('app.url') . "/dashboard#/attachments/{$attachment->id}",

        dispatch($transactionalEmailJob);
    }
}
