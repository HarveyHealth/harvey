<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', 'App\Http\Composers\CurrentUserViewComposer');
        view()->composer('*', 'App\Http\Composers\VueHelperViewComposer');
        view()->composer('*', 'App\Http\Composers\SVGImagesComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
