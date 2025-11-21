const mix = require("laravel-mix");

mix
    // Compile the main JS (supports Vue single-file components)
    .js("resources/js/app.js", "public/js")
    .vue() // enable .vue single-file component handling

    // Compile SASS/SCSS into public/css
    .sass("resources/sass/app.scss", "public/css")

    // Optionally extract vendor libs into a separate file for caching
    .extract(["vue", "axios", "vue-router"])

    // Source maps in development
    .sourceMaps(!mix.inProduction())

    // Versioning in production (cache busting)
    .version();
