<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
 */

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(), /*,
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect' ]*/
    ],
    function () {
        /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/

        Route::get('/', 'StartpageController@loadStartPage');

        Route::get('img/piwik.png', 'ImageController@generateImage');

        Route::get('impressum', function () {
            return view('impressum')
                ->with('title', trans('titles.impressum'))
                ->with('navbarFocus', 'kontakt');
        });
        Route::get('impressum.html', function () {
            return redirect(url('impressum'));
        });

        Route::get('about', function () {
            return view('about')
                ->with('title', trans('titles.about'))
                ->with('navbarFocus', 'kontakt');
        });
        Route::get('team', function () {
            return view('team.team')
                ->with('title', trans('titles.team'))
                ->with('navbarFocus', 'kontakt');
        });
        Route::get('team/pubkey-wsb', function () {
            return view('team.pubkey-wsb')
                ->with('title', trans('titles.team'))
                ->with('navbarFocus', 'kontakt');
        });

        Route::get('kontakt/{url?}', function ($url = "") {
            return view('kontakt.kontakt')
                ->with('title', trans('titles.kontakt'))
                ->with('navbarFocus', 'kontakt')
                ->with('url', $url);
        });

        Route::post('kontakt', 'MailController@contactMail');

        Route::get('tor', function () {
            return view('tor')
                ->with('title', 'tor hidden service - MetaGer')
                ->with('navbarFocus', 'dienste');
        });
        Route::get('spende', function () {
            return view('spende.spende')
                ->with('title', trans('titles.spende'))
                ->with('navbarFocus', 'foerdern');
        });
        Route::get('spende/danke/{data}', ['as' => 'danke', function ($data) {
            return view('spende.danke')
                ->with('title', trans('titles.spende'))
                ->with('navbarFocus', 'foerdern')
                ->with('data', unserialize(base64_decode($data)));
        }]);
        Route::get('partnershops', function () {
            return view('spende.partnershops')
                ->with('title', trans('titles.partnershops'))
                ->with('navbarFocus', 'foerdern');
        });

        Route::get('beitritt', function () {
            return view('spende.beitritt')
                ->with('title', trans('titles.beitritt'))
                ->with('navbarFocus', 'foerdern');
        });

        Route::get('bform1.htm', function () {
            return redirect('beitritt');
        });
        Route::get('spendenaufruf', function () {
            return view('spende.spendenaufruf')
                ->with('title', 'Spendenaufruf - MetaGer')
                ->with('navbarFocus', 'foerdern');
        });

        Route::post('spende', 'MailController@donation');

        Route::get('datenschutz', function () {
            return view('datenschutz')
                ->with('title', trans('titles.datenschutz'))
                ->with('navbarFocus', 'datenschutz');
        });

        Route::get('hilfe', function () {
            return view('hilfe')
                ->with('title', trans('titles.hilfe'))
                ->with('navbarFocus', 'hilfe');
        });

        Route::get('faq', function () {
            return view('faq')
                ->with('title', trans('titles.faq'))
                ->with('navbarFocus', 'hilfe');
        });

        Route::get('widget', function () {
            return view('widget.widget')
                ->with('title', trans('titles.widget'))
                ->with('navbarFocus', 'dienste');
        });

        Route::get('sitesearch', 'SitesearchController@loadPage');

        Route::get('websearch', function () {
            return view('widget.websearch')
                ->with('title', trans('titles.websearch'))
                ->with('navbarFocus', 'dienste');
        });

        Route::get('zitat-suche', 'ZitatController@zitatSuche');

        Route::group([/*'middleware' => ['referer.check'],*/ 'prefix' => 'admin'], function () {
            Route::get('/', 'AdminInterface@index');
            Route::match(['get','post'], 'count', 'AdminInterface@count');
            Route::get('check', 'AdminInterface@check');
            Route::get('engines', 'AdminInterface@engines');
        });

        Route::get('settings', 'StartpageController@loadSettings');

        Route::match(['get', 'post'], 'meta/meta.ger3', 'MetaGerSearch@search');
        Route::get('noaccess/{redirect}', 'MetaGerSearch@botProtection');
        Route::get('meta/picture', 'Pictureproxy@get');
        Route::get('clickstats', 'LogController@clicklog');
        Route::get('pluginClose', 'LogController@pluginClose');
        Route::get('pluginInstall', 'LogController@pluginInstall');

        Route::get('qt', 'MetaGerSearch@quicktips');
        Route::get('tips', 'MetaGerSearch@tips');
        Route::get('/plugins/{params}/opensearch.xml', 'StartpageController@loadPlugin');
        Route::get('owi', function () {
            return redirect('https://metager.de/klassik/en/owi/');
        });
        Route::get('MG20', function () {
            return redirect('https://metager.de/klassik/MG20');
        });
        Route::get('databund', function () {
            return redirect('https://metager.de/klassik/databund');
        });
        Route::get('languages', 'LanguageController@createOverview');
        Route::get('synoptic/{exclude?}', 'LanguageController@createSynopticEditPage');
        Route::post('synoptic/{exclude?}', 'LanguageController@processSynopticPageInput');
        Route::get('languages/edit/{from}/{to}/{exclude?}/{email?}', 'LanguageController@createEditPage');
        Route::post('languages/edit/{from}/{to}/{exclude?}/{email?}', 'MailController@sendLanguageFile');
        Route::get('berlin', 'StartpageController@berlin');

        Route::group(['prefix' => 'app'], function () {
            Route::get('/', function () {
                return view('app')
                    ->with('title', trans('titles.app'))
                    ->with('navbarFocus', 'dienste');
            });
            Route::get('metager', function () {
                $filePath = storage_path() . "/app/public/MetaGer-release.apk";
                return response()->download($filePath, "MetaGer-release.apk");
            });

            Route::get('maps', function () {
                $filePath     = env('maps_app');
                $fileContents = file_get_contents($filePath);
                return response($fileContents, 200)
                    ->header('Cache-Control', 'public')
                    ->header('Content-Type', 'application/vnd.android.package-archive')
                    ->header('Content-Transfer-Encoding', 'Binary')
                    ->header("Content-Disposition", "attachment; filename=app-release.apk");
            });
            Route::get('maps/version', function () {
                $filePath     = env('maps_version');
                $fileContents = file_get_contents($filePath);
                return response($fileContents, 200)
                    ->header('Content-Type', 'text/plain');
            });
        });
    });
