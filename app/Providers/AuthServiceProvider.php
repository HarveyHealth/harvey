<?php

namespace App\Providers;

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
        \App\Models\Appointment::class => \App\Policies\AppointmentPolicy::class,
        \App\Models\Attachment::class => \App\Policies\AttachmentPolicy::class,
        \App\Models\DiscountCode::class => \App\Policies\DiscountCodePolicy::class,
        \App\Models\Intake::class => \App\Policies\IntakePolicy::class,
        \App\Models\Invoice::class => \App\Policies\InvoicePolicy::class,
        \App\Models\InvoiceItem::class => \App\Policies\InvoiceItemPolicy::class,
        \App\Models\LabOrder::class => \App\Policies\LabOrderPolicy::class,
        \App\Models\LabTest::class => \App\Policies\LabTestPolicy::class,
        \App\Models\LabTestResult::class => \App\Policies\LabTestResultPolicy::class,
        \App\Models\Message::class => \App\Policies\MessagePolicy::class,
        \App\Models\Patient::class => \App\Policies\PatientPolicy::class,
        \App\Models\Practitioner::class => \App\Policies\PractitionerPolicy::class,
        \App\Models\Prescription::class => \App\Policies\PrescriptionPolicy::class,
        \App\Models\SKU::class => \App\Policies\SkuPolicy::class,
        \App\Models\SoapNote::class => \App\Policies\SoapNotePolicy::class,
        \App\Models\Test::class => \App\Policies\TestPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
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
