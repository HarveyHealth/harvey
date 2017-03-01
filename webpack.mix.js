const { mix } = require('laravel-mix');

mix.js('resources/assets/js/app_public.js', 'public/js')
     .js('resources/assets/js/app_logged_in.js', 'public/js')
     .extract(['vue', 'axios', 'vue-router'], 'public/js/module_extract.js')
     .combine([
                'resources/assets/js/vendors/facebook.js',
                'resources/assets/js/vendors/googleanalytics.js',
                'resources/assets/js/vendors/intercom.js',
                'resources/assets/js/vendors/mixpanel.js'
        ], 'public/js/vendors.js')
     .sass('resources/assets/sass/app_public.scss', 'public/css')
     .sass('resources/assets/sass/app_logged_in.scss', 'public/css')
     .browserSync('https://harvey.app');

if (mix.config.inProduction) {
        mix.version();
}