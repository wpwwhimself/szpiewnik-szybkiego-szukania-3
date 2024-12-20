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

mix.ts('frontend/roots/set.tsx', 'public/js/react/set.js')
    .ts('frontend/roots/song.tsx', 'public/js/react/song.js')
    .ts('frontend/roots/ordinarius.tsx', 'public/js/react/ordinarius.js')
    .react();
