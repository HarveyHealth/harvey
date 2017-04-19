<?php

namespace App\Providers;

use App\Lib\ZipCodeValidator;
use App\Models\User;
use App\Observers\UserObserver;
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
        // load any blade extensions
        require base_path('extensions/blade.php');
        require base_path('extensions/validator.php');
        
        User::observe(UserObserver::class);
        
        Validator::extend('serviceable', function ($attribute, $value, $parameters, $validator) {
            return $validator = app()->make(ZipCodeValidator::class)->setZip($value)->isServiceable();
        });
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
