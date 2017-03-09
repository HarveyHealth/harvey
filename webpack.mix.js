const { mix } = require('laravel-mix');

mix.js('resources/assets/js/app_public.js', 'public/js')
    .js('resources/assets/js/app_logged_in.js', 'public/js')
    .extract(['vue', 'axios', 'vue-router'], 'public/js/vendors/vendors_module_extract.js')
    .combine([
                'resources/assets/js/vendors/facebook.js',
                'resources/assets/js/vendors/googleanalytics.js',
                'resources/assets/js/vendors/intercom.js',
                'resources/assets/js/vendors/mixpanel.js'
        ], 'public/js/vendors/vendors_production_only.js')
    .copy([
            'resources/assets/js/vendors/modernizr-custom.js',
            'resources/assets/js/vendors/typekit.js'
        ], 'public/js/vendors')
    .sass('resources/assets/sass/app_public.scss', 'public/css')
    .sass('resources/assets/sass/app_logged_in.scss', 'public/css')
    .sass('resources/assets/sass/pages/legal.scss', 'public/css')
    .copy('resources/assets/images', 'public/images', false)
    .browserSync('https://harvey.app');

if (mix.config.inProduction) {
        mix.version();
}