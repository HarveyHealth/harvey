<?php

namespace App\Listeners;

use App\Events\AppointmentScheduled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendTransactionalEmail;

class SendPractitionerAppointmentEmail implements ShouldQueue
{
    protected $sendTransactionalEmail;

    public function __construct(SendTransactionalEmail $sendTransactionalEmail)
    {
        $this->sendTransactionalEmail = $sendTransactionalEmail;
    }

    public function handle(AppointmentScheduled $event)
    {
        $this->sendTransactionalEmail
            ->setTo($event->appointment->practitioner->user->email)
            ->setTemplate('practitioner.appointment.new')
            ->setTemplateModel([
            'appointment_date' => $event->appointment->practitionerAppointmentAtDate()->format('l F j'),
            'appointment_time' => $event->appointment->practitionerAppointmentAtDate()->format('h:i A'),
            'patient_name' => $event->appointment->patient->user->fullName(),
            'patient_phone' => $event->appointment->patient->user->phone,
            'practitioner_name' => $event->appointment->practitioner->user->first_name,
        ]);

        dispatch($this->sendTransactionalEmail);
    }
}
