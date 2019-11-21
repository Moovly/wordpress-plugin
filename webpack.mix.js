let mix = require("laravel-mix");

mix
  .sass("assets/sass/app.scss", "moovly.css")
  .js("assets/js/back/app.js", "moovly-plugin.js")
  .js("assets/js/front/vue/index.js", "moovly-custom.js")
  .js("assets/js/front/index.js", "moovly.js")
  .copyDirectory("assets/images", "dist/images")
  .setPublicPath("dist");
