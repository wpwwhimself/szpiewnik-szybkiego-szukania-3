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

mix.ts('frontend/roots/set.tsx', 'public/js/set.js')
    .ts('frontend/roots/song.tsx', 'public/js/song.js')
    .ts('frontend/roots/ordinarius.tsx', 'public/js/ordinarius.js')
    .react();
