let mix = require('laravel-mix');

mix
    .sass('src/assets/sass/app.scss', 'moovly.css')
    .js('src/assets/js/app.js', 'moovly.js')
    .setPublicPath('dist');
