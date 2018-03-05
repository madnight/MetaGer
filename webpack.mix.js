const {mix} = require('laravel-mix');

mix.options({
  postCss: [
    require('postcss-discard-comments')({
      removeAll: true
    })
  ],
  uglify: {
    topLevel: true
  }
});

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
  .less('resources/assets/less/default.less', 'public/css/themes/default.css')
  .less('resources/assets/less/metager/beitritt.less', 'public/css/beitritt.css')
  .less('resources/assets/less/utility.less', 'public/css/utility.css')
  .styles(['resources/assets/css/material-default.css'], 'public/css/material-default.css')
  .styles(['resources/assets/css/material-inverse.css'], 'public/css/material-inverse.css')
  .scripts(['resources/assets/js/scriptSubPages.js', 'resources/assets/js/translations.js'], 'public/js/scriptSubPages.js')
  .scripts(['resources/assets/js/results.js', 'resources/assets/js/scriptStartPage.js'], 'public/js/scriptStartPage.js')
  .scripts(['resources/assets/js/results.js', 'resources/assets/js/scriptResultPage.js'], 'public/js/scriptResultPage.js')
  .scripts(['resources/assets/js/utility.js'], 'public/js/utility.js')
  .scripts(['resources/assets/js/scriptJoinPage.js'], 'public/js/scriptJoinPage.js')
  .scripts(['resources/assets/js/editLanguage.js'], 'public/js/editLanguage.js')
  .scripts(['resources/assets/js/settings.js'], 'public/js/settings.js')
  .scripts(['resources/assets/js/widgets.js'], 'public/js/widgets.js')
  .sourceMaps(false, 'inline-source-map')
  .version();

mix.combine(['resources/assets/js/lib/jquery.js', 'resources/assets/js/lib/bootstrap.js', 'resources/assets/js/lib/md5.js', 'resources/assets/js/lib/iframeResizer.min.js'], 'public/js/lib.js');
