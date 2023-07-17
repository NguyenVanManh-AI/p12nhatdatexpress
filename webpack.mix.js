const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableNotifications();

// mix.options({
//     processCssUrls: false
// });

// mix.copyDirectory('resources/assets/images', 'public/images');
// mix.copyDirectory('resources/assets/fonts', 'public/fonts');

mix.js([
      'resources/assets/js/chat-box.js',
    ], 'public/js')
    .sass('resources/assets/sass/chat-box.scss', 'public/css')
    .version();
