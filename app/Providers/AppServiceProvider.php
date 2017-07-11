<?php

namespace App\Providers;

use App\Models\{Appointment, LabTest, Message, User};
use App\Observers\{AppointmentObserver, LabTestObserver, MessageObserver, UserObserver};
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\ServiceProvider;
use Validator;

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
        LabTest::observe(LabTestObserver::class);
        Message::observe(MessageObserver::class);
        User::observe(UserObserver::class);
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

        // bugsnag
        $this->app->alias('bugsnag.multi', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.multi', \Psr\Log\LoggerInterface::class);
    }
}
