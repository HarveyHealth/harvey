/* ==== COMMANDS ==== */
/* ---- defined in package.json ---- */
// `npm run dev`
// `npm run prod`

const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');
require('laravel-elixir-imagemin');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

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
       .browserSync({proxy: 'https://harvey.app'});
});
