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
        if ($this->app->environment('local')) {
            $version_id = time();
        } else {
            $version_id = \Cache::remember('app:version_id', 60*24*364, function () {
                $path = base_path('version.txt');

                if (file_exists($path)) {
                    $version_id = \File::lastModified($path);
                } else {
                    $version_id = time();
                }

                return $version_id;
            });
        }

        $this->app->version_id = $version_id;
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
