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
    PractitionerSchedule,
    PractitionerScheduleOverride,
    Prescription,
    SKU,
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
    PractitionerScheduleOverridePolicy,
    PractitionerSchedulePolicy,
    PrescriptionPolicy,
    SkuPolicy,
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
        Attachment::class => AttachmentPolicy::class,
        LabOrder::class => LabOrderPolicy::class,
        LabTest::class => LabTestPolicy::class,
        Message::class => MessagePolicy::class,
        Patient::class => PatientPolicy::class,
        Practitioner::class => PractitionerPolicy::class,
        PractitionerSchedule::class => PractitionerSchedulePolicy::class,
        PractitionerScheduleOverride::class => PractitionerScheduleOverridePolicy::class,
        Prescription::class => PrescriptionPolicy::class,
        SKU::class => SkuPolicy::class,
        SoapNote::class => SoapNotePolicy::class,
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
