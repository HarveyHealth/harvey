<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/*
 * This provider loads any helper files in the helpers folder
 */

class HelperServiceProvider extends ServiceProvider
{
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
        foreach (glob(base_path() . '/helpers/*.php') as $filename) {
            require_once($filename);
        }
    }
}
