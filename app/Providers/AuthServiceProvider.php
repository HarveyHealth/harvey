<?php

namespace App\Providers;

use App\Models\{
    Appointment,
    Attachment,
    DiscountCode,
    Invoice,
    InvoiceItem,
    LabOrder,
    LabTest,
    LabTestResult,
    Message,
    Patient,
    Practitioner,
    Prescription,
    SKU,
    SoapNote,
    Test,
    User
};
use App\Policies\{
    AppointmentPolicy,
    AttachmentPolicy,
    DiscountCodePolicy,
    IntakePolicy,
    InvoiceItemPolicy,
    InvoicePolicy,
    LabOrderPolicy,
    LabTestPolicy,
    LabTestResultPolicy,
    MessagePolicy,
    PatientPolicy,
    PractitionerPolicy,
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
        DiscountCode::class => DiscountCodePolicy::class,
        Invoice::class => InvoicePolicy::class,
        InvoiceItem::class => InvoiceItemPolicy::class,
        LabOrder::class => LabOrderPolicy::class,
        LabTest::class => LabTestPolicy::class,
        LabTestResult::class => LabTestResultPolicy::class,
        Message::class => MessagePolicy::class,
        Patient::class => PatientPolicy::class,
        Practitioner::class => PractitionerPolicy::class,
        Prescription::class => PrescriptionPolicy::class,
        SKU::class => SkuPolicy::class,
        SoapNote::class => SoapNotePolicy::class,
        Test::class => TestPolicy::class,
        User::class => UserPolicy::class,

        // Policies without a model.
        'intake' => IntakePolicy::class,
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
