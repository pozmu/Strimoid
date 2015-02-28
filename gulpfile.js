var elixir = require('laravel-elixir');

require('laravel-elixir-bower');

elixir(function(mix) {
    mix.bower('vendor.css', 'public/assets/css', 'vendor.js', 'public/assets/js')
       .stylesIn('resources/assets/css', 'public/assets/css')
       .scripts([
            'plugins/*.js',
            'modules/*.js',
            'lara.js'
       ], 'public/assets/js', 'resources/assets/js')
       .version([
            'assets/css/all.css',
            'assets/css/vendor.css',
            'assets/js/all.js',
            'assets/js/vendor.js'
       ]);
});

elixir(function(mix) {
    mix.copy('bower_components/bootstrap/dist/fonts', 'public/assets/fonts')
       .copy('bower_components/bootstrap/dist/fonts', 'public/build/assets/fonts');
});
