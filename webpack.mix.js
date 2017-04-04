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

mix.js('resources/assets/js/main.js', 'public/js')
   .copy([
        'resources/assets/js/vendors/modernizr-custom.js',
        'resources/assets/js/vendors/typekit.js'
    ], 'public/js/vendors')
   .sass('resources/assets/sass/app.scss', 'public/css');
