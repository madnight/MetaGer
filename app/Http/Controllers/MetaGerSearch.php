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
        $focus = $request->input("focus", "web");
        
        if ($focus === "maps") {
            $searchinput = $request->input('eingabe', '');
            return redirect()->to('https://maps.metager.de/map/' . $searchinput . '/1240908.5493525574,6638783.2192695495,6');
        }

        if ($focus !== "angepasst" && $this->startsWith($focus, "focus_")) {
            $metager->parseFormData($request);
            return $metager->createView();
        }
        #die($request->header('User-Agent'));
        $time = microtime();
        # Mit gelieferte Formulardaten parsen und abspeichern:
        $metager->parseFormData($request);

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

    public function get($url)
    {
	   $ctx = stream_context_create(array('http'=>array('timeout' => 2,)));
        return file_get_contents($url, false, $ctx);
    }

    private function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
}
