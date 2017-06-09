<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\LabTest;
use App\Models\Message;
use App\Models\Patient;
use App\Models\Practitioner;
use App\Models\Test;
use App\Models\User;
use App\Policies\AppointmentPolicy;
use App\Policies\LabTestPolicy;
use App\Policies\MessagePolicy;
use App\Policies\PatientPolicy;
use App\Policies\PractitionerPolicy;
use App\Policies\TestPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Appointment::class => AppointmentPolicy::class,
        LabTest::class => LabTestPolicy::class,
        Message::class => MessagePolicy::class,
        Patient::class => PatientPolicy::class,
        Practitioner::class => PractitionerPolicy::class,
        Test::class => TestPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
    }
}
