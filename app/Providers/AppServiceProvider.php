<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Message;
use App\Observers\AppointmentObserver;
use App\Observers\MessageObserver;
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
        Message::observe(MessageObserver::class);
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
