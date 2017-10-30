<?php

namespace App\Providers;

use App\Models\{
    Appointment, LabOrder, LabTest, Message, Patient, Practitioner, SKU, Test, User
};
use App\Policies\{
    AppointmentPolicy, LabTestPolicy, LabOrderPolicy, MessagePolicy, PatientPolicy, PractitionerPolicy, SkuPolicy, TestPolicy, UserPolicy
};
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
        LabOrder::class => LabOrderPolicy::class,
        LabTest::class => LabTestPolicy::class,
        Message::class => MessagePolicy::class,
        Patient::class => PatientPolicy::class,
        Practitioner::class => PractitionerPolicy::class,
        Test::class => TestPolicy::class,
        User::class => UserPolicy::class,
        SKU::class => SkuPolicy::class,
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
