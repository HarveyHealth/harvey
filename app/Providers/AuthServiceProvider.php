<?php

namespace App\Providers;

use App\Models\{
    Appointment,
    Attachment,
    LabOrder,
    LabTest,
    Message,
    Patient,
    Practitioner,
    Prescription,
    SoapNote,
    Test,
    User
};
use App\Policies\{
    AppointmentPolicy,
    AttachmentPolicy,
    LabOrderPolicy,
    LabTestPolicy,
    MessagePolicy,
    PatientPolicy,
    PractitionerPolicy,
    PrescriptionPolicy,
    SoapNotePolicy,
    TestPolicy,
    UserPolicy
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
        Attachment::class => AttachmentPolicity::class,
        LabOrder::class => LabOrderPolicy::class,
        LabTest::class => LabTestPolicy::class,
        Message::class => MessagePolicy::class,
        Patient::class => PatientPolicy::class,
        Practitioner::class => PractitionerPolicy::class,
        Prescription::class => PrescriptionPolicity::class,
        SoapNote::class => SoapNotePolicity::class,
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
