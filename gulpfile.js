/* ==== COMMANDS ==== */
/* ---- defined in package.json ---- */
// `npm run dev`
// `npm run prod`

const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');
require('laravel-elixir-imagemin');

elixir((mix) => {
    mix.sass('app.scss')
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
        .scriptsIn('resources/assets/js/scripts', 'public/js/vendors.js')
        .browserSync({proxy: 'https://harvey.app'});
});
