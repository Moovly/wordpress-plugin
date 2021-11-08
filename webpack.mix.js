let mix = require("laravel-mix");
mix.webpackConfig({
  resolve: { fallback: { crypto: false } },
});
mix.setPublicPath("dist");

mix.sass("assets/sass/app.scss", "moovly.css");
mix.js("assets/js/back/app.js", "moovly-plugin.js").vue();
mix.js("assets/js/front/index.js", "moovly.js").vue();
mix.copyDirectory("assets/images", "dist/images");
