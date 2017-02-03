/* ==== COMMANDS ==== */
/* ---- defined in package.json ---- */
// `npm run dev`
// `npm run prod`

const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');
require('laravel-elixir-imagemin');
require('laravel-elixir-spritesmith');

elixir((mix) => {
    mix.imagemin({
        optimizationLevel: 3,
        progressive: true,
        interlaced: true
    })
    .spritesmith('resources/assets/sprite', {
        imgOutput: 'public/images/sprite',
        cssOutput: 'resources/assets/sass',
        cssName: '_sprite.scss',
        retinaSrcFilter: Elixir.config.assetsPath + '/sprite/*@2x.png',
        retinaImgName: 'sprite@2x.png',
        imgPath: '../../images/sprite/sprite.png',
        retinaImgPath: '../../images/sprite/sprite@2x.png'
    })
    .sass('app_public.scss')
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
    .scriptsIn(
        'resources/assets/js/scripts',
        'public/js/vendors.js')
    .browserSync({proxy: 'https://harvey.app'});

    if (elixir.config.production) {
        mix.version([
            'public/js/vendors.js',
            'public/js/app_public.js',
            'public/js/app_logged_in.js',
            'public/css/app_public.css',
            'public/css/app_logged_in.css']);
    }
});
