let mix = require('laravel-mix');

mix.js('src/assets/js/app.js', 'moovly.js')
    .setPublicPath('dist');
