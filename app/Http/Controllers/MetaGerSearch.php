<?php

namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\MetaGer;
use Illuminate\Http\Request;
use LaravelLocalization;

class MetaGerSearch extends Controller
{
    public function search(Request $request, MetaGer $metager)
    {
        #die($request->header('User-Agent'));
        $time = microtime();
        # Mit gelieferte Formulardaten parsen und abspeichern:
        $metager->parseFormData($request);
        #if($metager->getFokus() !== "bilder" )
        #{
        # Nach Spezialsuchen überprüfen:
        $metager->checkSpecialSearches($request);
        #}
        # Alle Suchmaschinen erstellen
        $metager->createSearchEngines($request);

        # Alle Ergebnisse vor der Zusammenführung ranken:
        $metager->rankAll();

        # Ergebnisse der Suchmaschinen kombinieren:
        $metager->combineResults();

        # Die Ausgabe erstellen:
        return $metager->createView();
    }

    public function quicktips(Request $request)
    {
        $q = $request->input('q', '');

        # Zunächst den Spruch
        $spruecheFile = storage_path() . "/app/public/sprueche.txt";
        if (file_exists($spruecheFile) && $request->has('sprueche')) {
            $sprueche = file($spruecheFile);
            $spruch   = $sprueche[array_rand($sprueche)];
        } else {
            $spruch = "";
        }

        # Die manuellen Quicktips:
        $file = storage_path() . "/app/public/qtdata.csv";

        $mquicktips = [];
        if (file_exists($file) && $q !== '') {
            $file = fopen($file, 'r');
            while (($line = fgetcsv($file)) !== false) {
                $words = array_slice($line, 3);
                $isIn  = false;
                foreach ($words as $word) {
                    $word = strtolower($word);
                    if (strpos($q, $word) !== false) {
                        $isIn = true;
                        break;
                    }
                }
                if ($isIn === true) {
                    $quicktip          = array('QT_Type' => "MQT");
                    $quicktip["URL"]   = $line[0];
                    $quicktip["title"] = $line[1];
                    $quicktip["descr"] = $line[2];
                    $mquicktips[]      = $quicktip;
                }
            }
            fclose($file);
        }

        # Wikipedia Quicktip
        $quicktips = [];
        if (App::isLocale('en')) {
            $url = "https://en.wikipedia.org/w/api.php?action=opensearch&search=" . urlencode($q) . "&limit=1&namespace=0&format=json";
        } else {
            $url = "https://de.wikipedia.org/w/api.php?action=opensearch&search=" . urlencode($q) . "&limit=1&namespace=0&format=json";
        }

        $decodedResponse = json_decode($this->get($url), true);
        if (isset($decodedResponse[1][0]) && isset($decodedResponse[2][0]) && isset($decodedResponse[3][0])) {
            $quicktip           = [];
            $quicktip["title"]  = $decodedResponse[1][0];
            $quicktip["URL"]    = $decodedResponse[3][0];
            $quicktip["descr"]  = $decodedResponse[2][0];
            $quicktip['gefVon'] = "aus <a href=\"https://de.wikipedia.org\" target=\"_blank\">Wikipedia, der freien Enzyklopädie</a>";

            $quicktips[] = $quicktip;
        }

        $mquicktips = array_merge($mquicktips, $quicktips);

        # Wussten Sie schon:
        $NUMBER_OF_TIPS = 21;

        $title = trans('tips.shorttitle');
        # Workaround für eine variable Tipanzahl trotz Übersetzungsdateien:
        # Es wird so lange versucht den nächsten Tip zu lesen, bis dieser "leer" ist, also mit dem Key übereinstimmt.
        $tips = [];
        for ($i = 1;; $i++) {
            $nextTip = trans('tips.tip.' . $i);
            if ($nextTip == 'tips.tip.' . $i) {
                break;
            } else {
                $tips[] = $nextTip;
            }
        }
        $tip          = $tips[array_rand($tips)];
        $mquicktips[] = ['title' => $title, 'descr' => $tip, 'URL' => '/tips'];

        # Werbelinks:
        $file = storage_path() . "/app/public/ads.txt";
        if (file_exists($file)) {
            $ads = json_decode(file_get_contents($file), true);
            $ad  = $ads[array_rand($ads)];

            $mquicktips[] = ['title' => $ad['title'], 'descr' => $ad['descr'], 'URL' => $ad['URL']];
        }

        # Spendenaufruf:
        $mquicktips[] = ['title' => trans('quicktip.spende.title'), 'descr' => trans('quicktip.spende.descr'), 'URL' => LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "spendenaufruf")];

        return view('quicktip')
            ->with('spruch', $spruch)
            ->with('mqs', $mquicktips);

    }

    public function tips()
    {
        $tips = [];
        for ($i = 1;; $i++) {
            $nextTip = trans('tips.tip.' . $i);
            if ($nextTip == 'tips.tip.' . $i) {
                break;
            } else {
                $tips[] = $nextTip;
            }
        }
        return view('tips')
            ->with('title', 'MetaGer - Tipps & Tricks')
            ->with('tips', $tips);
    }

    public function get($url)
    {
        return file_get_contents($url);
    }

}
