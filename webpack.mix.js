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

   .js('resources/assets/vue/app_public.js', 'public/js')
   .js('resources/assets/vue/app.js', 'public/js')
   .js('resources/assets/vue/pages/signup/main.js', 'public/js/vue')
   .js('resources/assets/vue/vendors/modernizr-custom.js', 'public/js/vendors')
   .js('resources/assets/vue/vendors/intercom.js', 'public/js/vendors')
   .js('resources/assets/vue/vendors/typekit.js', 'public/js/vendors')

   .js('resources/assets/js/vendors/zepto.js', 'public/js/vendors')
   .js('resources/assets/js/vendors/lity.js', 'public/js/vendors')
   .copy('resources/assets/images', 'public/images', false)
   .sass('resources/assets/css/vendors/lity.css', 'public/css/vendors')
   .sass('resources/assets/scss/application.scss', 'public/css')
   .sass('resources/assets/scss/public/pages/legal.scss', 'public/css')
   .sass('resources/assets/scss/public/app_public.scss', 'public/css');

if (mix.config.inProduction) {
    mix.version();
}
