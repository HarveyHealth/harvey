let mix = require('laravel-mix');
let SpritesmithPlugin = require('webpack-spritesmith');
let path = require('path');

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
   .copy('resources/assets/images', 'public/images', false)
   .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}

/*
 |--------------------------------------------------------------------------
 | Custom Webpack Configuration
 |--------------------------------------------------------------------------
 |
 | Mix provides a useful webpackConfig method that allows you to merge any short
 | Webpack config overrides. This is a particularly appealing choice, it
 | doesn't require you to copy and maintain a copy of the webpack.config.js file.
 |
 */

mix.webpackConfig({
    resolve: {
        alias: {
            moment$: 'moment/moment.js'
        },
    },
    plugins: [
        new SpritesmithPlugin({
            src: {
                cwd: path.resolve(__dirname, 'resources/assets/sprite'),
                glob: '*.png'
            },
            target: {
                image: path.resolve(__dirname, 'public/images/sprite.png'),
                css: path.resolve(__dirname, 'resources/assets/scss/public/_sprite.scss')
            },
            retina: '@2x',
            apiOptions: {
                cssImageRef: "../../images/sprite.png"
            }
        })
    ]
});
