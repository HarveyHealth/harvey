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

mix.options({
        processCssUrls: false
    })
    .js('resources/assets/js/pages/signup/main.js', 'public/js/signup')
   .js('resources/assets/js/pages/schedule/main.js', 'public/js/schedule')
   .js('resources/assets/legacy/js/app_public.js', 'public/legacy/js')
   .js('resources/assets/legacy/js/app_logged_in.js', 'public/legacy/js')
   .copy([
        'resources/assets/js/vendors/modernizr-custom.js',
        'resources/assets/js/vendors/typekit.js'
    ], 'public/js/vendors')
   .copy('resources/assets/legacy/images', 'public/images', false)
   .sass('resources/assets/scss/application.scss', 'public/css')
   .sass('resources/assets/legacy/sass/app_public.scss', 'public/legacy/css');
