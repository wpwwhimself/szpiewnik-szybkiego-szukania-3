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

mix.ts('resources/js/set.tsx', 'public/js')
    .ts('resources/js/song.tsx', 'public/js')
    .ts('resources/js/ordinarius.tsx', 'public/js')
    .react();
