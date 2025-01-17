<?php

namespace App\Providers;

use App\Lib\Slack;
use Illuminate\Support\ServiceProvider;

class SlackServiceProvider extends ServiceProvider
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
        $this->app->singleton('slack', function ($app) {
            return new Slack;
        });
    }
}
