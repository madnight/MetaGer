const elixir = require('laravel-elixir');
require('laravel-elixir-vue-2');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir(function(mix) {
    mix.less('default.less', 'public/css/themes/default.css');
    mix.scripts(['lib/jquery.js', 'lib/bootstrap.js', 'widgets.js', 'editLanguage.js', 'kontakt.js', 'lib/lightslider.js', 'lib/masonry.js', 'lib/imagesloaded.js', 'lib/openpgp.min.js', 'scriptResultPage.js', 'scriptStartPage.js', 'settings.js', 'lib/iframeResizer.min.js' /*, 'lib/vue/app.js', 'lib/vue/bootstrap.js'*/ ]);
    mix.scripts(['lib/jquery.js', 'lib/iframeResizer.contentWindow.min.js'], 'public/js/quicktips.js');
    mix.version(['css/themes/default.css', 'js/all.js', 'js/quicktips.js']);
});