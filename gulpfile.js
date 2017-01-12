/* ==== COMMANDS ==== */
/* ---- defined in package.json ---- */
// `npm run dev`
// `npm run prod`

const elixir = require('laravel-elixir');
const bootstrap = require('bootstrap-styl');

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
    mix.stylus('app.styl', null, { use: [ bootstrap() ] })
       .webpack('app.js');
});
