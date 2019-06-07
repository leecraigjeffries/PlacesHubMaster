const mix = require('laravel-mix');

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

mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/js/vendor/jquery.js')
    .copy('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', 'public/js/vendor/bootstrap.js')
    .copy('node_modules/jsrender/jsrender.min.js', 'public/js/vendor/jsrender.js')
    .copy('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js', 'public/js/vendor/data-tables-bs4.js')
    .copy('node_modules/flatpickr/dist/flatpickr.min.js', 'public/js/vendor/flatpickr.js')
    .copy('node_modules/datatables.net/js/jquery.dataTables.min.js', 'public/js/vendor/data-tables.js')
    .sass('resources/sass/app.scss', 'public/css/app')
    .sass('resources/sass/dev.scss', 'public/css/app')
    .scripts(['resources/js/throttle-debounce.js', 'resources/js/app.js'], 'public/js/app/app.js');
