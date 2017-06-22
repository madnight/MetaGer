const elixir = require('laravel-elixir')
require('laravel-elixir-vue-2')
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
elixir(function (mix) {
  mix.less('default.less', 'public/css/themes/default.css')
  /*
   | Not mixed (specific pages only)
   |
   | widgets.js
   | editLanguage.js
   | kontakt.js
   | scriptResultPage.js
   | scriptStartPage.js
   | settings.js
  */
  mix.scripts(['lib/jquery.js', 'lib/jquery-ui.min.js', 'lib/bootstrap.js', 'lib/lightslider.js', 'lib/masonry.js', 'lib/imagesloaded.js', 'lib/openpgp.min.js', 'lib/iframeResizer.min.js', 'lib/md5.js'], 'public/js/lib.js')
  mix.scripts(['lib/jquery.js', 'lib/iframeResizer.contentWindow.min.js'], 'public/js/quicktips.js')
  mix.scripts(['scriptStartPage.js', 'results.js'], 'public/js/scriptStartPage.js');
  mix.scripts(['scriptResultPage.js', 'results.js'], 'public/js/scriptResultPage.js');
  mix.scripts(['scriptSubpages.js'], 'public/js/scriptSubpages.js');
  mix.version(['css/themes/default.css', 'js/lib.js', 'js/quicktips.js']);
  mix.less('metager/beitritt.less', 'public/css/beitritt.css')
  mix.version(['css/beitritt.css'])
  mix.version(['js/widgets.js', 'js/editLanguage.js', 'js/kontakt.js', 'js/scriptResultPage.js', 'js/scriptStartPage.js', 'js/settings.js'])
})
