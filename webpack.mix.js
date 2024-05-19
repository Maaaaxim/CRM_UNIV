const mix = require('laravel-mix');

mix.styles([
    'resources/assets/plugins/fontawesome-free/css/all.min.css',
    'resources/assets/css/adminlte.min.css',
    'resources/assets/plugins/select2/css/select2.min.css',
], 'public/assets/css/admin.css');


mix.scripts([
    'resources/assets/plugins/jquery/jquery.min.js',
    'resources/assets/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'resources/assets/js/adminlte.min.js',
    'resources/assets/plugins/select2/js/select2.min.js',
    'resources/assets/js/demo.js'
], 'public/assets/js/admin.js');

mix.copyDirectory('resources/assets/plugins/fontawesome-free/webfonts', 'public/assets/webfonts');
mix.copyDirectory('resources/assets/img', 'public/assets/img');
mix.copy('resources/assets/css/adminlte.min.css.map', 'public/assets/css/adminlte.min.css.map');
mix.copy('resources/assets/js/adminlte.min.js.map', 'public/assets/js/adminlte.min.js.map');



