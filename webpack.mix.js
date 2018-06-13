let mix = require('laravel-mix');

mix
    .sass('assets/sass/app.scss', 'moovly.css')
    .js('assets/js/app.js', 'moovly.js')
    .setPublicPath('dist');
