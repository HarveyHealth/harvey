<?php

namespace August\Pitbull;

use Illuminate\Support\ServiceProvider;

class PitbullServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Set up any publishable files
        $this->publishes([
            __DIR__ . '/config/pitbull.php' => config_path('pitbull.php'),
        ]);

        // load migrations
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/routes/pitbull.php');

        // load our views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'pitbull');

        // blade extensions
        $this->registerBladeExtensions();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        // merge in the default config
        $this->mergeConfigFrom(__DIR__ . '/config/pitbull.php', 'pitbull');

        $this->app->singleton('pitbull', function ($app) {
            return $app->make('\August\Pitbull\Pitbull');
        });
    }

    /**
     * Register Blade extensions
     */
     protected function registerBladeExtensions()
     {
         $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

         // @role('role')
         $blade->directive('role', function ($expression) {
             return "<?php if (Auth::check() && Auth::user()->roleIs({$expression})): ?>";
         });
         $blade->directive('endrole', function () {
             return "<?php endif; ?>";
         });

         // @permission('permission')
         $blade->directive('permission', function ($expression) {
             return "<?php if (Auth::check() && Auth::user()->may({$expression})): ?>";
         });
         $blade->directive('endpermission', function () {
             return "<?php endif; ?>";
         });

         // @allowed('allowed', $post)
         $blade->directive('allowed', function ($expression) {
             return "<?php if (Auth::check() && Auth::user()->allowed({$expression})): ?>";
         });
         $blade->directive('endallowed', function () {
             return "<?php endif; ?>";
         });
     }
}
