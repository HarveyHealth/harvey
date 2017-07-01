<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SMSServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sms', function ($app) {
            return $app->make('\App\Lib\SMS');
        });
    }
}
