/* ==== COMMANDS ==== */
/* ---- defined in package.json ---- */
// `npm run dev`
// `npm run prod`

const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');
require('laravel-elixir-imagemin');

elixir((mix) => {
    mix.sass('app_public.scss')
        .sass('app_logged_in.scss')
        .webpack('app_public.js')
        .webpack('app_logged_in.js', null, null, {
            resolve: {
                alias: {
                    'vue$': 'vue/dist/vue.js'
                }
            },
            devtool: '#source-map'
        })
        .imagemin({
            optimizationLevel: 3,
            progressive: true,
            interlaced: true
        })
        .scriptsIn(
            'resources/assets/js/scripts',
            'public/js/vendors.js')
        .version([
            'public/js/vendors.js',
            'public/js/app_public.js',
            'public/js/app_logged_in.js',
            'public/css/app_public.css',
            'public/css/app_logged_in.css'])
        .browserSync({proxy: 'https://harvey.app'});
});
