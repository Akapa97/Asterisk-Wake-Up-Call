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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

mix.scripts([
    'node_modules/datatables.net/js/jquery.dataTables.js',
    'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js',
], 'public/js/datatables.js');

mix.scripts([
  'node_modules/moment/moment.js',
  'node_modules/moment/locale/pt.js',
], 'public/js/moment.js');

mix.scripts([
  'node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.js',
], 'public/js/tempusdominus-bootstrap-4.js');

mix.styles([
  'node_modules/font-awesome/css/font-awesome.css',
], 'public/css/font-awesome.css');

mix.styles([
    'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css',
], 'public/css/datatables.css');

mix.styles([
  'node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.css',
], 'public/css/tempusdominus-bootstrap-4.css');

mix.copyDirectory('node_modules/font-awesome/fonts', 'public/fonts');

if (mix.inProduction()) {
    mix.version();
}
