let mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/assets/js')
   .setPublicPath('public');
