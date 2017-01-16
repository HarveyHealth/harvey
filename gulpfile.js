/* ==== COMMANDS ==== */
/* ---- defined in package.json ---- */
// `npm run dev`
// `npm run prod`

const elixir = require('laravel-elixir');
const bootstrap = require('bootstrap-styl');
const browserSync = require('laravel-elixir-browsersync2');

require('laravel-elixir-vue-2');

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
       .webpack('app.js', null, null, {
           resolve: {
               alias: {
                   'vue$': 'vue/dist/vue.js'
               }
           },
           devtool: '#source-map'
       })
       .browserSync({proxy: 'localhost:8000'});
});
