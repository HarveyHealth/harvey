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

   .js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/app_public.js', 'public/js')
   .sass('resources/assets/scss/application.scss', 'public/css')
   .sass('resources/assets/scss/public/app_public.scss', 'public/css')

// Needed for cross browsers (Safari)
// mix.sourceMaps();

if (mix.config.inProduction) {
    mix.version();
}
