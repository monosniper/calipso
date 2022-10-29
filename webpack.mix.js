const mix = require('laravel-mix');
require('dotenv').config()

mix.autoload({
    jquery: ['$', 'window.jQuery']
});

mix
    // Set default dist path to public
    .setPublicPath('public/assets')

    // Compile app.js with bootstrap into single app.js file
    .js([
        'resources/js/app.js',
    ], 'js/app.js')
    .minify('public/assets/js/app.js')

    // Compile tailwind css
    .postCss('resources/css/tailwind.css', 'css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])

    // Compile scss
    .sass('resources/scss/pages/home.scss', 'css/pages')
    .sass('resources/scss/pages/profile.scss', 'css/pages')
    .sass('resources/scss/pages/shop.scss', 'css/pages')
    .sass('resources/scss/pages/lot.scss', 'css/pages')
    .sass('resources/scss/pages/freelance.scss', 'css/pages')
    .sass('resources/scss/pages/order.scss', 'css/pages')
    .sass('resources/scss/pages/freelancers.scss', 'css/pages')
    .sass('resources/scss/pages/projects.scss', 'css/pages')
    .sass('resources/scss/pages/faq.scss', 'css/pages')
    .sass('resources/scss/pages/about.scss', 'css/pages')
    .sass('resources/scss/pages/reviews.scss', 'css/pages')
    .sass('resources/scss/pages/statistics.scss', 'css/pages')
    .sass('resources/scss/pages/safe.scss', 'css/pages')
    .sass('resources/scss/pages/help.scss', 'css/pages')

    .sass('resources/scss/app.scss', 'css/app.css')

    // Generate source maps
    .sourceMaps()

    // Disable windows notifications about compile
    .disableNotifications()

    // Live reload browser on each update in code
    .browserSync('http://127.0.0.1:8000');
