<?php

namespace App\Providers;

use Validator;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // load any blade extensions
        require base_path('extensions/blade.php');
        require base_path('extensions/validator.php');

        $this->setVersionNumber();
    }

    protected function setVersionNumber()
    {
        $numbers = [];
        $filepaths =  [
            public_path() . '/js/app_logged_in.js',
            public_path() . '/js/app_public.js',
            public_path() . '/css/app.css'
        ];

        foreach ($filepaths as $path) {
            if (file_exists($path)) {
                $numbers[] = \File::lastModified($path);
            }
        }

        $this->app->version_id = max($numbers);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
        // bugsnag
        $this->app->alias('bugsnag.multi', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.multi', \Psr\Log\LoggerInterface::class);
    }
}
