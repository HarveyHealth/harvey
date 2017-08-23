<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserRegistered' => [
            'App\Listeners\SendWelcomeEmail',
        ],

        'App\Events\AppointmentScheduled' => [
            'App\Listeners\SendPatientAppointmentEmail',
            'App\Listeners\SendPractitionerAppointmentEmail',
            'App\Listeners\NotifyAppointmentSlackChannel',
            'App\Listeners\CreateAppointmentCalendarEvent',
        ],

        'App\Events\AppointmentCanceled' => [
            'App\Listeners\SendPatientAppointmentCanceledEmail',
            'App\Listeners\SendPractitionerAppointmentCanceledEmail',
            'App\Listeners\NotifyAppointmentCanceledSlackChannel',
            'App\Listeners\DeleteAppointmentCalendarEvent',
        ],

        'App\Events\AppointmentUpdated' => [
            'App\Listeners\SendPatientAppointmentUpdatedEmail',
            'App\Listeners\SendPractitionerAppointmentUpdatedEmail',
            'App\Listeners\NotifyAppointmentUpdatedSlackChannel',
            'App\Listeners\UpdateAppointmentCalendarEvent',
        ],

        'App\Events\AppointmentComplete' => [
            'App\Listeners\ChargeUserForCompleteAppointment',
        ],

        'App\Events\LabOrderApproved' => [
            'App\Listeners\ChargeUserForLabOrder',
        ],

        'App\Events\OutOfServiceZipCodeRegistered' => [
            'App\Listeners\CreateLead',
        ],

        'App\Events\PhoneNumberChanged' => [
            'App\Listeners\SendPhoneNumberValidationCode',
        ],

        'App\Events\ChargeFailed' => [
            'App\Listeners\NotifyOfFailedCharge',
        ],
    ];

    protected $subscribe = [
        'App\Listeners\UserEventSubscriber',
    ];
}
