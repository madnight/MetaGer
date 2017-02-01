<?php

namespace App\Http\Controllers;

use App;
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

        # Ein Schutz gegen bestimmte Bot-Angriffe, die uns passiert sind.
        if ($metager->doBotProtection($request->input('bot', ""))) {
            return redirect(LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), url("/noaccess", ['redirect' => base64_encode(url()->full())])));
        }

        # Nach Spezialsuchen überprüfen:
        $metager->checkSpecialSearches($request);

        # Alle Suchmaschinen erstellen
        $metager->createSearchEngines($request);

        # Alle Ergebnisse vor der Zusammenführung ranken:
        $metager->rankAll();

        # Ergebnisse der Suchmaschinen kombinieren:
        $metager->prepareResults();

        # Die Ausgabe erstellen:
        return $metager->createView();
    }

    public function botProtection($redirect)
    {
        $hash = md5(date('YmdHi'));
        return view('botProtection')
            ->with('hash', $hash)
            ->with('r', $redirect);
    }

    public function quicktips(Request $request, MetaGer $metager)
    {
        $q = $request->input('q', '');

        # Spruch
        $spruecheFile = storage_path() . "/app/public/sprueche.txt";
        if (file_exists($spruecheFile) && $request->has('sprueche')) {
            $sprueche = file($spruecheFile);
            $spruch   = $sprueche[array_rand($sprueche)];
        } else {
            $spruch = "";
        }

        # manuelle Quicktips:
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
                    $quicktip            = array('QT_Type' => "MQT");
                    $quicktip["URL"]     = $line[0];
                    $quicktip["title"]   = $line[1];
                    $quicktip["summary"] = $line[2];
                    $mquicktips[]        = $quicktip;
                }
            }
            fclose($file);
        }

        $quicktips = [];

        # Wetter
        try {
            $url = "http://api.openweathermap.org/data/2.5/weather?type=accurate&units=metric&lang=" . APP::getLocale() . "&q=" . urlencode($q) . "&APPID=" . getenv("openweathermap");

            $result = json_decode($this->get($url), true);

            $searchWords = explode(' ', $q);
            $within      = false;
            foreach ($searchWords as $word) {
                if (stripos($result["name"], $word) !== false) {
                    $within = true;
                }
            }
            if ($within) {
                $weather          = [];
                $weather["title"] = "Wetter in " . $result["name"];
                $weather["URL"]   = "http://openweathermap.org/city/" . $result["id"];

                $summary = '<b class="detail-short">' . $result["main"]["temp"] . " °C, " . $result["weather"][0]["description"] . "</b>";
                $details = '<table  class="table table-condensed"><tr><td>Temperatur</td><td>' . $result["main"]["temp_min"] . " bis " . $result["main"]["temp_max"] . " °C</td></tr>";
                $details .= "<tr><td>Druck</td><td>" . $result["main"]["pressure"] . " hPa</td></tr>";
                $details .= "<tr><td>Luftfeuchtigkeit</td><td>" . $result["main"]["humidity"] . " %</td></tr>";
                $details .= "<tr><td>Wind</td><td>" . $result["wind"]["speed"] . " m/s, " . $result["wind"]["deg"] . "°</td></tr>";
                $details .= "<tr><td>Bewölkung</td><td>" . $result["clouds"]["all"] . " %</td></tr>";
                if (isset($result->rain)) {
                    $details .= " | Regen letzte drei Stunden: " . $result["rain"]["3h"] . " h";
                }
                $details .= "</table>";
                $weather["summary"]   = $summary;
                $weather["details"]   = $details;
                $weather["gefVon"]    = "von <a href = \"https://openweathermap.org\" target=\"_blank\" rel=\"noopener\">Openweathermap</a>";
                $requestData          = [];
                $requestData["url"]   = "http://openweathermap.org/img/w/";
                $weather["image"]     = action('Pictureproxy@get', $requestData) . $result["weather"][0]["icon"] . ".png";
                $weather["image-alt"] = $result["weather"][0]["main"];
                $mquicktips[]         = $weather;
            }
        } catch (\ErrorException $e) {

        }

        # Wikipedia Quicktip
        $url             = "https://" . APP::getLocale() . ".wikipedia.org/w/api.php?action=opensearch&search=" . urlencode($q) . "&limit=10&namespace=0&format=json&redirects=resolve";
        $decodedResponse = json_decode($this->get($url), true);
        if (isset($decodedResponse[1][0]) && isset($decodedResponse[2][0]) && isset($decodedResponse[3][0])) {
            $quicktip     = [];
            $firstSummary = $decodedResponse[2][0];
            // Wenn es mehr als ein Ergebnis gibt
            if (isset($decodedResponse[1][1])) {
                // Solange noch zusätzliche Seiten vorhanden sind, füge sie der Tabelle hinzu
                $details = '<table class=table table-condensed>';
                for ($i = 1;isset($decodedResponse[1][$i]) && isset($decodedResponse[2][$i]) && isset($decodedResponse[3][$i]); $i++) {
                    $details .= '<tr><td><a href="' . $decodedResponse[3][$i] . '" target="_blank" rel="noopener">' . $decodedResponse[1][$i] . '</a></td></tr>';
                }
                $details .= '</table>';
                $quicktip["title"]   = $decodedResponse[1][0];
                $quicktip["URL"]     = $decodedResponse[3][0];
                $quicktip["summary"] = $decodedResponse[2][0];
                $quicktip["details"] = $details;
                $quicktip['gefVon']  = trans('metaGerSearch.quicktips.wikipedia.adress');
            } else {
                $quicktip["title"]   = $decodedResponse[1][0];
                $quicktip["URL"]     = $decodedResponse[3][0];
                $quicktip["summary"] = $decodedResponse[2][0];
                $quicktip['gefVon']  = trans('metaGerSearch.quicktips.wikipedia.adress');
            }
            $quicktips[] = $quicktip;
        }
        $mquicktips = array_merge($mquicktips, $quicktips);

        # Dict.cc Quicktip
        if (count(explode(' ', $q)) < 3) {
            $url             = "http://www.dict.cc/metager.php?s=" . urlencode($q);
            $decodedResponse = json_decode($this->get($url), true);
            if ($decodedResponse["headline"] != "" && $decodedResponse["link"] != "") {
                $quicktip            = [];
                $quicktip["title"]   = $decodedResponse["headline"];
                $quicktip["URL"]     = $decodedResponse["link"];
                $quicktip["summary"] = implode(", ", $decodedResponse["translations"]);
                $quicktip['gefVon']  = trans('metaGerSearch.quicktips.dictcc.adress');

                if (App::isLocale('de')) {
                    array_unshift($mquicktips, $quicktip);
                } else {
                    $mquicktips[] = $quicktip;
                }
            }
        }

        # wussten Sie schon
        $file = storage_path() . "/app/public/tips.txt";
        if (file_exists($file)) {
            $tips = file($file);
            $tip  = $tips[array_rand($tips)];

            $mquicktips[] = ['title' => trans('metaGerSearch.quicktips.tips.title'), 'summary' => $tip, 'URL' => '/tips'];
        }

        # Werbelinks
        $file = storage_path() . "/app/public/ads.txt";
        if (file_exists($file)) {
            $ads = json_decode(file_get_contents($file), true);
            $ad  = $ads[array_rand($ads)];
            if (isset($ads['details'])) {
                $mquicktips[] = ['title' => $ad['title'], 'summary' => $ad['summary'], 'details' => $ad['details'], 'URL' => $ad['URL']];
            } else {
                $mquicktips[] = ['title' => $ad['title'], 'summary' => $ad['summary'], 'URL' => $ad['URL']];
            }
        }

        # Spendenaufruf:
        //$mquicktips[] = ['title' => trans('quicktip.spende.title'), 'summary' => trans('quicktip.spende.descr'), 'URL' => LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "spendenaufruf")];

        return view('quicktip')
            ->with('spruch', $spruch)
            ->with('mqs', $mquicktips);

    }

    public function tips()
    {
        $file = storage_path() . "/app/public/tips.txt";
        $tips = [];
        if (file_exists($file)) {
            $tips = file($file);
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
