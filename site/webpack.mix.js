let mix = require('laravel-mix');

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

mix
    .js('resources/assets/js/app.js', 'public/js/app.js')
    .extract(['vue'])
    .less('resources/assets/less/app.less', 'public/css')
    .browserSync({
        open: 'local',
        proxy: 'rcon2.test',
        notify: false,
        files: [
            'public/**/*.css',
            'public/**/*.js',
            'resources/**/*'
        ]
    })
    .disableNotifications();


