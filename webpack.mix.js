let mix = require('laravel-mix');

mix.sass('resources/sass/app.scss', 'public/assets/css');
mix.minify('public/assets/css/app.css');
