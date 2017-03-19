<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Test;
use App\Models\User;
use App\Policies\AppointmentPolicy;
use App\Policies\PatientPolicy;
use App\Policies\TestPolicy;
use App\Policies\UserPolicy;
use Carbon\Carbon;
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
        User::class => UserPolicy::class,
        Patient::class => PatientPolicy::class,
        Appointment::class => AppointmentPolicy::class,
        Test::class => TestPolicy::class
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
        Passport::tokensExpireIn(Carbon::now()->addDays(15));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
