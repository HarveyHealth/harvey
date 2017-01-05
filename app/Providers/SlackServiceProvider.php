<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Lib\Slack;

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
        $this->app->singleton(Slack::class, function ($app) {
            return new Slack;
        });
    }
}
