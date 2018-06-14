let mix = require('laravel-mix');

mix
    .sass('assets/sass/app.scss', 'moovly.css')
    .js('assets/js/back/app.js', 'moovly-plugin.js')
    .js('assets/js/front/app.js', 'moovly.js')
    .setPublicPath('dist');
