const { mix } = require('laravel-mix');

mix.js('resources/assets/js/app_public.js', 'public/js')
   .js('resources/assets/js/app_logged_in.js', 'public/js')
   .extract(['vue', 'axios', 'vue-router'], 'public/js/vendor.js')
   .sass('resources/assets/sass/app_public.scss', 'public/css')
   .sass('resources/assets/sass/app_logged_in.scss', 'public/css')
   .browserSync('https://harvey.app');

if (mix.config.inProduction) {
    mix.version();
}
// combine extra scripts to app_public.js app_logged_in.js
// vendor scripts
// import sass files to vue components
// live reload https://github.com/JeffreyWay/laravel-mix/blob/master/docs/livereload.md
// imagemin
// // spritesmith
// .imagemin({
//         optimizationLevel: 3,
//         progressive: true,
//         interlaced: true
//     })
//     .spritesmith('resources/assets/sprite', {
//         imgOutput: 'public/images/sprite',
//         cssOutput: 'resources/assets/sass',
//         cssName: '_sprite.scss',
//         retinaSrcFilter: Elixir.config.assetsPath + '/sprite/*@2x.png',
//         retinaImgName: 'sprite@2x.png',
//         imgPath: '../../images/sprite/sprite.png',
//         retinaImgPath: '../../images/sprite/sprite@2x.png'
//     })