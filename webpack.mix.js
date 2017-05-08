const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.options({processCssUrls: false})
   .js('resources/assets/js/pages/appointments/main.js', 'public/js/appointments')
   .js('resources/assets/js/pages/dashboard/main.js', 'public/js/dashboard')
   .js('resources/assets/js/pages/signup/main.js', 'public/js/signup')
   .js('resources/assets/js/pages/schedule/main.js', 'public/js/schedule')
   .js('resources/assets/legacy/js/app_public.js', 'public/legacy/js')
   .js('resources/assets/legacy/js/app_logged_in.js', 'public/legacy/js')
   .js('resources/assets/js/vendors/modernizr-custom.js', 'public/js/vendors')
   .js('resources/assets/js/vendors/intercom.js', 'public/js/vendors')
   .js('resources/assets/js/vendors/typekit.js', 'public/js/vendors')
   .copy('resources/assets/images', 'public/images', false)
   .sass('resources/assets/scss/application.scss', 'public/css')
   .sass('resources/assets/legacy/sass/pages/legal.scss', 'public/legacy/css')
   .sass('resources/assets/legacy/sass/app_public.scss', 'public/legacy/css');

if (mix.config.inProduction) {
    mix.version();
}
