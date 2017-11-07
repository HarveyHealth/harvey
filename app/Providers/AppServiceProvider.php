<?php

namespace App\Providers;

use App\Models\{
    Appointment,
    LabTest,
    Message,
    User,
    LabOrder,
    LabTestInformation
};
use App\Observers\{
    AppointmentObserver,
    LabTestObserver,
    MessageObserver,
    UserObserver,
    LabOrderObserver,
    LabTestInformationObserver
};
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;
use Shippo, Validator;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require base_path('extensions/blade.php');
        require base_path('extensions/validator.php');

        Appointment::observe(AppointmentObserver::class);
        LabOrder::observe(LabOrderObserver::class);
        LabTest::observe(LabTestObserver::class);
        LabTestInformation::observe(LabTestInformationObserver::class);
        Message::observe(MessageObserver::class);
        User::observe(UserObserver::class);

        Shippo::setApiKey(config('services.shippo.key'));
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing', 'dev')) {
            $this->app->register(DuskServiceProvider::class);
        }

        $this->app->alias('bugsnag.multi', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.multi', \Psr\Log\LoggerInterface::class);
    }
}
