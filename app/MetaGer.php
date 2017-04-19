<?php
namespace App;

use App;
use Cache;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use LaravelLocalization;
use Log;
use Predis\Connection\ConnectionException;
use Illuminate\Support\Facades\Redis;

class MetaGer
{
    # Einstellungen für die Suche
    protected $fokus;
    protected $eingabe;
    protected $q;
    protected $category;
    protected $time;
    protected $page;
    protected $lang;
    protected $cache = "";
    protected $site;
    protected $hostBlacklist   = [];
    protected $domainBlacklist = [];
    protected $stopWords       = [];
    protected $phrases         = [];
    protected $engines         = [];
    protected $results         = [];
    protected $ads             = [];
    protected $products        = [];
    protected $warnings        = [];
    protected $errors          = [];
    protected $addedHosts      = [];
    protected $startCount      = 0;
    protected $canCache        = false;
    # Daten über die Abfrage
    protected $ip;
    protected $language;
    protected $agent;
    # Konfigurationseinstellungen:
    protected $sumaFile;
    protected $mobile;
    protected $resultCount;
    protected $sprueche;
    protected $maps;
    protected $newtab;
    protected $domainsBlacklisted = [];
    protected $urlsBlacklisted    = [];
    protected $url;
    protected $languageDetect;

    public function __construct()
    {
        # Timer starten
        $this->starttime = microtime(true);

        # Versuchen Blacklists einzulesen
        if (file_exists(config_path() . "/blacklistDomains.txt") && file_exists(config_path() . "/blacklistUrl.txt")) {
            $tmp                      = file_get_contents(config_path() . "/blacklistDomains.txt");
            $this->domainsBlacklisted = explode("\n", $tmp);
            $tmp                      = file_get_contents(config_path() . "/blacklistUrl.txt");
            $this->urlsBlacklisted    = explode("\n", $tmp);
        } else {
            Log::warning("Achtung: Eine, oder mehrere Blacklist Dateien, konnten nicht geöffnet werden");
        }

        # Parser Skripte einhängen
        $dir = app_path() . "/Models/parserSkripte/";
        foreach (scandir($dir) as $filename) {
            $path = $dir . $filename;
            if (is_file($path)) {
                require_once $path;
            }
        }

        # Cachebarkeit testen
        try {
            Cache::has('test');
            $this->canCache = true;
        } catch (ConnectionException $e) {
            $this->canCache = false;
        }
    }

    # Erstellt aus den gesammelten Ergebnissen den View
    public function createView()
    {
        $viewResults = [];
        # Wir extrahieren alle notwendigen Variablen und geben Sie an unseren View:
        foreach ($this->results as $result) {
            $viewResults[] = get_object_vars($result);
        }

        # Wir müssen natürlich noch den Log für die durchgeführte Suche schreiben:
        $this->createLogs();

        if ($this->fokus === "bilder") {
            switch ($this->out) {
                case 'results':
                    return view('metager3bilderresults')
                        ->with('results', $viewResults)
                        ->with('eingabe', $this->eingabe)
                        ->with('mobile', $this->mobile)
                        ->with('warnings', $this->warnings)
                        ->with('errors', $this->errors)
                        ->with('metager', $this)
                        ->with('browser', (new Agent())->browser());
                default:
                    return view('metager3bilder')
                        ->with('results', $viewResults)
                        ->with('eingabe', $this->eingabe)
                        ->with('mobile', $this->mobile)
                        ->with('warnings', $this->warnings)
                        ->with('errors', $this->errors)
                        ->with('metager', $this)
                        ->with('browser', (new Agent())->browser());
            }
        } else {
            switch ($this->out) {
                case 'results':
                    return view('metager3results')
                        ->with('results', $viewResults)
                        ->with('eingabe', $this->eingabe)
                        ->with('mobile', $this->mobile)
                        ->with('warnings', $this->warnings)
                        ->with('errors', $this->errors)
                        ->with('metager', $this)
                        ->with('browser', (new Agent())->browser());
                    break;
                case 'results-with-style':
                    return view('metager3')
                        ->with('results', $viewResults)
                        ->with('eingabe', $this->eingabe)
                        ->with('mobile', $this->mobile)
                        ->with('warnings', $this->warnings)
                        ->with('errors', $this->errors)
                        ->with('metager', $this)
                        ->with('suspendheader', "yes")
                        ->with('browser', (new Agent())->browser());
                    break;
                case 'rss20':
                    return view('metager3resultsrss20')
                        ->with('results', $viewResults)
                        ->with('eingabe', $this->eingabe)
                        ->with('metager', $this)
                        ->with('resultcount', sizeof($viewResults));
                    break;
                case 'result-count':
                    # Wir geben die Ergebniszahl und die benötigte Zeit zurück:
                    return sizeof($viewResults) . ";" . round((microtime(true) - $this->starttime), 2);
                    break;
                default:
                    return view('metager3')
                        ->with('eingabe', $this->eingabe)
                        ->with('mobile', $this->mobile)
                        ->with('warnings', $this->warnings)
                        ->with('errors', $this->errors)
                        ->with('metager', $this)
                        ->with('browser', (new Agent())->browser());
                    break;
            }
        }
    }

    public function prepareResults()
    {
        $engines = $this->engines;

        // combine
        $combinedResults = $this->combineResults($engines);

        # Wir bestimmen die Sprache eines jeden Suchergebnisses
        $this->results = $this->addLangCodes($this->results);

        // sort
        //$sortedResults = $this->sortResults($engines);
        // filter
        // augment (boost&adgoal)
        // authorize
        // misc (WiP)
        if ($this->fokus == "nachrichten") {
            $this->results = array_filter($this->results, function ($v, $k) {
                return !is_null($v->getRank());
            }, ARRAY_FILTER_USE_BOTH);
            uasort($this->results, function ($a, $b) {
                $datea = $a->getDate();
                $dateb = $b->getDate();
                return $dateb - $datea;
            });
        } else {
            uasort($this->results, function ($a, $b) {
                if ($a->getRank() == $b->getRank()) {
                    return 0;
                }

                return ($a->getRank() < $b->getRank()) ? 1 : -1;
            });
        }

        # Validate Results
        $newResults = [];
        foreach ($this->results as $result) {
            if ($result->isValid($this)) {
                $newResults[] = $result;
            }

        }
        $this->results = $newResults;

        # Boost implementation
        $this->results = $this->parseBoost($this->results);

        #Adgoal Implementation
        $this->results = $this->parseAdgoal($this->results);

        $counter   = 0;
        $firstRank = 0;

        if (isset($this->startForwards)) {
            $this->startCount = $this->startForwards;
        } elseif (isset($this->startBackwards)) {
            $this->startCount = $this->startBackwards - count($this->results) - 1;
        } else {
            $this->startCount = 0;
        }

        foreach ($this->results as $result) {
            if ($counter === 0) {
                $firstRank = $result->rank;
            }

            $counter++;
            $result->number = $counter + $this->startCount;
            $confidence     = 0;
            if ($firstRank > 0) {
                $confidence = $result->rank / $firstRank;
            } else {
                $confidence = 0;
            }

            if ($confidence > 0.65) {
                $result->color = "#FF4000";
            } elseif ($confidence > 0.4) {
                $result->color = "#FF0080";
            } elseif ($confidence > 0.2) {
                $result->color = "#C000C0";
            } else {
                $result->color = "#000000";
            }

        }

        if (LaravelLocalization::getCurrentLocale() === "en") {
            $this->ads = [];
        }

        $this->validated = false;
        if (isset($this->password)) {
            # Wir bieten einen bezahlten API-Zugriff an, bei dem dementsprechend die Werbung ausgeblendet wurde:
            # Aktuell ist es nur die Uni-Mainz. Deshalb überprüfen wir auch nur diese.
            $password       = getenv('mainz');
            $passwordBerlin = getenv('berlin');
            $eingabe        = $this->eingabe;
            $password       = md5($eingabe . $password);
            $passwordBerlin = md5($eingabe . $passwordBerlin);
            if ($this->password === $password || $this->password === $passwordBerlin) {
                $this->ads       = [];
                $this->products  = [];
                $this->validated = true;
                $this->maps      = false;
            }
        }

        if (count($this->results) <= 0) {
            $this->errors[] = trans('metaGer.results.failed');
        }

        if ($this->canCache() && isset($this->next) && count($this->next) > 0 && count($this->results) > 0) {
            $page       = $this->page + 1;
            $this->next = [
                'page'          => $page,
                'startForwards' => $this->results[count($this->results) - 1]->number,
                'engines'       => $this->next,
            ];
            Cache::put(md5(serialize($this->next)), serialize($this->next), 60);
        } else {
            $this->next = [];
        }

    }

    private function addLangCodes($results)
    {
        # Wenn es keine Ergebnisse gibt, brauchen wir uns gar nicht erst zu bemühen
        if (sizeof($results) === 0) {
            return $results;
        }

        # Bei der Spracheinstellung "all" wird nicht gefiltert
        if ($this->getLang() === "all") {
            return $results;
        } else {
            # Ansonsten müssen wir jedem Result einen Sprachcode hinzufügen
            $id          = 0;
            $langStrings = [];
            foreach ($results as $result) {
                # Wir geben jedem Ergebnis eine ID um später die Sprachcodes zuordnen zu können
                $result->id = $id;

                $langStrings["result_" . $id] = utf8_encode($result->getLangString());

                $id++;
            }
            # Wir schreiben die Strings in eine temporäre JSON-Datei,
            # Da das Array unter umständen zu groß ist für eine direkte Übergabe an das Skript
            $filename = "/tmp/" . getmypid();
            file_put_contents($filename, json_encode($langStrings));
            $langDetectorPath = app_path() . "/Models/lang.pl";
            $lang             = exec("echo '$filename' | $langDetectorPath");
            $lang             = json_decode($lang, true);

            # Wir haben nun die Sprachcodes der einzelnen Ergebnisse.
            # Diese müssen wir nur noch korrekt zuordnen, dann sind wir fertig.
            foreach ($lang as $key => $langCode) {
                # Prefix vom Key entfernen:
                $id = intval(str_replace("result_", "", $key));
                foreach ($this->results as $result) {
                    if ($result->id === $id) {
                        $result->langCode = $langCode;
                        break;
                    }
                }
            }
            return $results;
        }
    }

    /**
     * Diese Funktion überprüft, ob wir einen erweiterten Check auf Bots machen müssen.
     * Z.B.: Wurden wir von einem Bot (dessen Anfragen aus dem Tor-Netzwerk kamen) mit tausenden
     * Anfragen zu Telefonnummern überschwemmt. Bei diesen werden wir nun eine erweiterte Überprüfung
     * durchführen.
     * Für den Anfang werden wir alle Anfragen, die unter diese Kriterien fallen, nur noch beantworten, wenn
     * JavaScript ausgeführt wird. (Mal schauen ob und wie lange dies ausreicht)
     */
    public function doBotProtection($bot)
    {
        $hash = md5(date('YmdHi'));

        $shouldCheck = false;

        foreach ($this->request->all() as $key => $value) {
            if (strpos($key, "amp;") !== false) {
                $shouldCheck = true;
                break;
            }
        }

        if ((preg_match("/^\d+$/s", $this->getEingabe()) || $shouldCheck) && $bot !== $hash) {
            return true;
        } else {
            return false;
        }

    }

    public function combineResults($engines)
    {
        foreach ($engines as $engine) {
            if (isset($engine->next)) {
                $this->next[] = $engine->next;
            }
            if (isset($engine->last)) {
                $this->last[] = $engine->last;
            }
            foreach ($engine->results as $result) {
                if ($result->valid) {
                    $this->results[] = $result;
                }
            }
            foreach ($engine->ads as $ad) {
                $this->ads[] = $ad;
            }
            foreach ($engine->products as $product) {
                $this->products[] = $product;
            }
        }

    }

    public function parseBoost($results)
    {
        foreach ($results as $result) {
            if (preg_match('/^(http[s]?\:\/\/)?(www.)?amazon\.de/', $result->anzeigeLink)) {
                if (preg_match('/\?/', $result->anzeigeLink)) {
                    $result->link .= '&tag=boostmg01-21';
                } else {
                    $result->link .= '?tag=boostmg01-21';
                }
                $result->partnershop = true;

            }
        }
        return $results;
    }

    public function parseAdgoal($results)
    {
        $publicKey  = getenv('adgoal_public');
        $privateKey = getenv('adgoal_private');
        if ($publicKey === false) {
            return $results;
        }
        $tldList = "";
        try {
            foreach ($results as $result) {
                $link = $result->anzeigeLink;
                if (strpos($link, "http") !== 0) {
                    $link = "http://" . $link;
                }
                $tldList .= parse_url($link, PHP_URL_HOST) . ",";
                $result->tld = parse_url($link, PHP_URL_HOST);
            }
            $tldList = rtrim($tldList, ",");

            # Hashwert
            $hash = md5("meta" . $publicKey . $tldList . "GER");

            # Query
            $query = urlencode($this->q);

            $link   = "https://api.smartredirect.de/api_v2/CheckForAffiliateUniversalsearchMetager.php?p=" . $publicKey . "&k=" . $hash . "&tld=" . $tldList . "&q=" . $query;
            $answer = json_decode(file_get_contents($link));

            # Nun müssen wir nur noch die Links für die Advertiser ändern:
            foreach ($answer as $el) {
                $hoster = $el[0];
                $hash   = $el[1];

                foreach ($results as $result) {
                    if ($hoster === $result->tld) {
                        # Hier ist ein Advertiser:
                        # Das Logo hinzufügen:
                        if ($result->image !== "") {
                            $result->logo = "https://img.smartredirect.de/logos_v2/60x30/" . $hash . ".gif";
                        } else {
                            $result->image = "https://img.smartredirect.de/logos_v2/120x60/" . $hash . ".gif";
                        }

                        # Den Link hinzufügen:
                        $publicKey = $publicKey;
                        $targetUrl = $result->anzeigeLink;
                        if (strpos($targetUrl, "http") !== 0) {
                            $targetUrl = "http://" . $targetUrl;
                        }

                        $gateHash            = md5($targetUrl . $privateKey);
                        $newLink             = "https://api.smartredirect.de/api_v2/ClickGate.php?p=" . $publicKey . "&k=" . $gateHash . "&url=" . urlencode($targetUrl) . "&q=" . $query;
                        $result->link        = $newLink;
                        $result->partnershop = true;
                    }
                }
            }
        } catch (\ErrorException $e) {
            return $results;
        }

        return $results;
    }

    /*
     * Die Erstellung der Suchmaschinen bis die Ergebnisse da sind mit Unterfunktionen
     */

    public function createSearchEngines(Request $request)
    {
        # Wenn es kein Suchwort gibt
        if (!$request->has("eingabe") || $this->q === "") {
            return;
        }

        $xml                  = simplexml_load_file($this->sumaFile);
        $sumas                = $xml->xpath("suma");
        $enabledSearchengines = [];
        $overtureEnabled      = false;
        $sumaCount            = 0;

        /* Erstellt die Liste der eingestellten Sumas
         * Der einzige Unterschied bei angepasstem Suchfokus ist,
         * dass nicht nach den Typen einer Suma,
         * sondern den im Request mitgegebenen Typen entschieden wird.
         * Ansonsten wird genau das selbe geprüft und gemacht:
         * Handelt es sich um spezielle Suchmaschinen die immer an sein müssen
         * Wenn es Overture ist vermerken dass Overture an ist
         * Suma Zähler erhöhen
         * Zu Liste hinzufügen
         */
        foreach ($sumas as $suma) {
            if (($this->sumaIsSelected($suma, $request)
                || (!$this->isBildersuche()
                    && $this->sumaIsAdsuche($suma, $overtureEnabled)))
                && (!$this->sumaIsDisabled($suma))) {
                if ($this->sumaIsOverture($suma)) {
                    $overtureEnabled = true;
                }
                if ($this->sumaIsNotAdsuche($suma)) {
                    $sumaCount += 1;
                }
                $enabledSearchengines[] = $suma;
            }
        }

        # Sonderregelung für alle Suchmaschinen, die zu den Minisuchern gehören. Diese können alle gemeinsam über einen Link abgefragt werden
        $subcollections = [];

        $tmp = [];
        // Es gibt den Schalter "minism=on" Dieser soll bewirken, dass alle Minisucher angeschaltet werden.
        // Wenn also "minism=on" ist, dann durchsuchen wir statt den tatsächlich angeschalteten Suchmaschinen,
        // alle Suchmaschinen nach "minismCollection"
        if ($request->input("minism", "off") === "on") {
            // Wir laden alle Minisucher
            foreach ($sumas as $engine) {
                if (isset($engine["minismCollection"])) {
                    $subcollections[] = $engine["minismCollection"]->__toString();
                }
            }
            # Nur noch alle eventuell angeschalteten Minisucher deaktivieren
            foreach ($enabledSearchengines as $index => $engine) {
                if (!isset($engine["minismCollection"])) {
                    $tmp[] = $engine;
                }
            }
        } else {
            // Wir schalten eine Teilmenge, oder aber gar keine an
            foreach ($enabledSearchengines as $engine) {
                if (isset($engine['minismCollection'])) {
                    $subcollections[] = $engine['minismCollection']->__toString();
                } else {
                    $tmp[] = $engine;
                }
            }
        }
        $enabledSearchengines = $tmp;
        if (sizeof($subcollections) > 0) {
            $enabledSearchengines[] = $this->loadMiniSucher($xml, $subcollections);
        }
        if ($sumaCount <= 0) {
            $this->errors[] = trans('metaGer.settings.noneSelected');
        }
        $engines = [];
        # Wenn eine Sitesearch durchgeführt werden soll, überprüfen wir ob überhaupt eine der Suchmaschinen eine Sitesearch unterstützt
        $siteSearchFailed = $this->checkCanNotSitesearch($enabledSearchengines);

        $typeslist = [];
        $counter   = 0;

        if ($this->requestIsCached($request)) {
            $engines = $this->getCachedEngines($request);
        } else {
            $engines = $this->actuallyCreateSearchEngines($enabledSearchengines, $siteSearchFailed);
        }

        # Wir starten alle Suchen
        foreach ($engines as $engine) {
            $engine->startSearch($this);
        }

        // Derzeit deaktiviert, da es die eigene Suche gibt
        // $this->adjustFocus($sumas, $enabledSearchengines);

        /* Wir warten auf die Antwort der Suchmaschinen
         * Die Verbindung steht zu diesem Zeitpunkt und auch unsere Requests wurden schon gesendet.
         * Wir zählen die Suchmaschinen, die durch den Cache beantwortet wurden:
         * $enginesToLoad zählt einerseits die Suchmaschinen auf die wir warten und andererseits
         * welche Suchmaschinen nicht rechtzeitig geantwortet haben.
         */

        $enginesToLoad = [];
        $canBreak      = false;
        foreach ($engines as $engine) {
            if ($engine->cached) {
                if ($overtureEnabled && ($engine->name === "overture" || $engine->name === "overtureAds")) {
                    $canBreak = true;
                }
            } else {
                $enginesToLoad[$engine->name] = false;
            }
        }

        $this->waitForResults($enginesToLoad, $overtureEnabled, $canBreak);

        $this->retrieveResults($engines);
    }

    # Spezielle Suchen und Sumas

    public function sumaIsSelected($suma, $request)
    {
        if ($this->fokus === "angepasst") {
            if ($request->has($suma["name"])) {
                return true;
            }
        } else {
            $types = explode(",", $suma["type"]);
            if (in_array($this->fokus, $types)) {
                return true;
            }
        }
        return false;
    }

    public function actuallyCreateSearchEngines($enabledSearchengines, $siteSearchFailed)
    {
        $engines = [];
        foreach ($enabledSearchengines as $engine) {

            # Wenn diese Suchmaschine gar nicht eingeschaltet sein soll
            if (!$siteSearchFailed
                && strlen($this->site) > 0
                && (!isset($engine['hasSiteSearch'])
                    || $engine['hasSiteSearch']->__toString() === "0")) {
                continue;
            }

            if (!isset($engine["package"])) {
                die(var_dump($engine));
            }
            # Setze Pfad zu Parser
            $path = "App\Models\parserSkripte\\" . ucfirst($engine["package"]->__toString());

            # Prüfe ob Parser vorhanden
            if (!file_exists(app_path() . "/Models/parserSkripte/" . ucfirst($engine["package"]->__toString()) . ".php")) {
                Log::error("Konnte " . $engine["name"] . " nicht abfragen, da kein Parser existiert");
                $this->errors[] = trans('metaGer.engines.noParser', ['engine' => $engine["name"]]);
                continue;
            }

            # Es wird versucht die Suchengine zu erstellen
            $time = microtime();
            try {
                $tmp = new $path($engine, $this);
            } catch (\ErrorException $e) {
                Log::error("Konnte " . $engine["name"] . " nicht abfragen. " . var_dump($e));
                continue;
            }

            # Ausgabe bei Debug-Modus
            if ($tmp->enabled && isset($this->debug)) {
                $this->warnings[] = $tmp->service . "   Connection_Time: " . $tmp->connection_time . "    Write_Time: " . $tmp->write_time . " Insgesamt:" . ((microtime() - $time) / 1000);
            }

            # Wenn die neu erstellte Engine eingeschaltet ist, wird sie der Liste hinzugefügt
            if ($tmp->isEnabled()) {
                $engines[] = $tmp;
            }
        }
        return $engines;
    }

    public function isBildersuche()
    {
        return $this->fokus === "bilder";
    }

    public function sumaIsAdsuche($suma, $overtureEnabled)
    {
        $sumaName = $suma["name"]->__toString();
        return
            $sumaName === "qualigo"
            || $sumaName === "similar_product_ads"
            || (!$overtureEnabled && $sumaName === "overtureAds")
            || $sumaName == "rlvproduct";
    }

    public function sumaIsDisabled($suma)
    {
        return
        isset($suma['disabled'])
        && $suma['disabled']->__toString() === "1";
    }

    public function sumaIsOverture($suma)
    {
        return
        $suma["name"]->__toString() === "overture"
        || $suma["name"]->__toString() === "overtureAds";
    }

    public function sumaIsNotAdsuche($suma)
    {
        return
        $suma["name"]->__toString() !== "qualigo"
        && $suma["name"]->__toString() !== "similar_product_ads"
        && $suma["name"]->__toString() !== "overtureAds";
    }

    public function requestIsCached($request)
    {
        return
        $request->has('next')
        && Cache::has($request->input('next'))
        && unserialize(Cache::get($request->input('next')))['page'] > 1;
    }

    public function getCachedEngines($request)
    {
        $next       = unserialize(Cache::get($request->input('next')));
        $this->page = $next['page'];
        $engines    = $next['engines'];
        if (isset($next['startForwards'])) {
            $this->startForwards = $next['startForwards'];
        }
        if (isset($next['startBackwards'])) {
            $this->startBackwards = $next['startBackwards'];
        }
        return $engines;
    }

    public function loadMiniSucher($xml, $subcollections)
    {
        $minisucherEngine             = $xml->xpath('suma[@name="minism"]')[0];
        $subcollections               = urlencode("(" . implode(" OR ", $subcollections) . ")");
        $minisucherEngine["formData"] = str_replace("<<SUBCOLLECTIONS>>", $subcollections, $minisucherEngine["formData"]);
        $minisucherEngine["formData"] = str_replace("<<COUNT>>", sizeof($subcollections) * 10, $minisucherEngine["formData"]);
        return $minisucherEngine;
    }

    # Passt den Suchfokus an, falls für einen Fokus genau alle vorhandenen Sumas eingeschaltet sind
    public function adjustFocus($sumas, $enabledSearchengines)
    {
        # Findet für alle Foki die enthaltenen Sumas
        $foki = []; # [fokus][suma] => [suma]
        foreach ($sumas as $suma) {
            if ((!$this->sumaIsDisabled($suma)) && (!isset($suma['userSelectable']) || $suma['userSelectable']->__toString() === "1")) {
                if (isset($suma['type'])) {
                    # Wenn foki für diese Suchmaschine angegeben sind
                    $focuses = explode(",", $suma['type']->__toString());
                    foreach ($focuses as $foc) {
                        if (isset($suma['minismCollection'])) {
                            $foki[$foc][] = "minism";
                        } else {
                            $foki[$foc][] = $suma['name']->__toString();
                        }
                    }
                } else {
                    # Wenn keine foki für diese Suchmaschine angegeben sind
                    if (isset($suma['minismCollection'])) {
                        $foki["andere"][] = "minism";
                    } else {
                        $foki["andere"][] = $suma['name']->__toString();
                    }
                }
            }
        }

        # Findet die Namen der aktuell eingeschalteten Sumas
        $realEngNames = [];
        foreach ($enabledSearchengines as $realEng) {
            $nam = $realEng["name"]->__toString();
            if ($nam !== "qualigo" && $nam !== "overtureAds" && $nam !== "rlvproduct") {
                $realEngNames[] = $nam;
            }
        }

        # Anschließend werden diese beiden Listen verglichen (jeweils eine der Fokuslisten für jeden Fokus), um herauszufinden ob sie vielleicht identisch sind. Ist dies der Fall, so hat der Nutzer anscheinend Suchmaschinen eines kompletten Fokus eingestellt. Der Fokus wird dementsprechend angepasst.
        foreach ($foki as $fok => $engines) {
            $isFokus      = true;
            $fokiEngNames = [];
            foreach ($engines as $eng) {
                $fokiEngNames[] = $eng;
            }
            # Jede eingeschaltete Engine ist für diesen Fokus geeignet
            foreach ($fokiEngNames as $fen) {
                # Bei Bildersuchen ist uns egal, ob alle Suchmaschinen aus dem Suchfokus eingeschaltet sind, da wir sie eh als Bildersuche anzeigen müssen
                if (!in_array($fen, $realEngNames) && $fok !== "bilder") {
                    $isFokus = false;
                }
            }
            # Jede im Fokus erwartete Engine ist auch eingeschaltet
            foreach ($realEngNames as $ren) {
                if (!in_array($ren, $fokiEngNames)) {
                    $isFokus = false;
                }
            }
            # Wenn die Listen identisch sind, setze den Fokus um
            if ($isFokus) {
                $this->fokus = $fok;
            }
        }
    }

    public function checkCanNotSitesearch($enabledSearchengines)
    {
        if (strlen($this->site) > 0) {
            $enginesWithSite = 0;
            foreach ($enabledSearchengines as $engine) {
                if (isset($engine['hasSiteSearch']) && $engine['hasSiteSearch']->__toString() === "1") {
                    $enginesWithSite++;
                }
            }
            if ($enginesWithSite === 0) {
                $this->errors[] = trans('metaGer.sitesearch.failed', ['site' => $this->site, 'searchLink' => $this->generateSearchLink("web", false)]);
                return true;
            } else {
                $this->warnings[] = trans('metaGer.sitesearch.success', ['site' => $this->site]);
                return false;
            }
        }
        return false;
    }

    public function waitForResults($enginesToLoad, $overtureEnabled, $canBreak)
    {
        $loadedEngines = 0;
        $timeStart     = microtime(true);

        # Auf wie viele Suchmaschinen warten wir?
        $engineCount = count($enginesToLoad);

        while (true) {
            $time          = (microtime(true) - $timeStart) * 1000;
            $loadedEngines = intval(Redis::hlen('search.' . $this->getHashCode()));
            if ($overtureEnabled && (Redis::hexists('search.' . $this->getHashCode(), 'overture') || Redis::hexists('search.' . $this->getHashCode(), 'overtureAds'))) {
                $canBreak = true;
            }

            # Abbruchbedingung
            if ($time < 500) {
                if (($engineCount === 0 || $loadedEngines >= $engineCount) && $canBreak) {
                    break;
                }

            } elseif ($time >= 500 && $time < $this->time) {
                if (($engineCount === 0 || ($loadedEngines / ($engineCount * 1.0)) >= 0.8) && $canBreak) {
                    break;
                }

            } else {
                break;
            }
            usleep(50000);
        }

        # Wir haben nun so lange wie möglich gewartet. Wir registrieren nun noch die Suchmaschinen, die geanwortet haben.
        $answered = Redis::hgetall('search.' . $this->getHashCode());
        foreach ($answered as $key => $value) {
            $enginesToLoad[$key] = true;
        }
        $this->enginesToLoad = $enginesToLoad;
    }

    public function retrieveResults($engines)
    {
        # Von geladenen Engines die Ergebnisse holen
        foreach ($engines as $engine) {
            if (!$engine->loaded) {
                try {
                    $engine->retrieveResults($this);
                } catch (\ErrorException $e) {
                    Log::error($e);
                }
            }
        }

        # Nicht fertige Engines verwefen
        foreach ($engines as $engine) {
            if (!$engine->loaded) {
                $engine->shutdown();
            }
        }

        $this->engines = $engines;
    }

/*
 * Ende Suchmaschinenerstellung und Ergebniserhalt
 */

    public function parseFormData(Request $request)
    {
        $this->request = $request;
        # Sichert, dass der request in UTF-8 formatiert ist
        if ($request->input('encoding', '') !== "utf8") {
            # In früheren Versionen, als es den Encoding Parameter noch nicht gab, wurden die Daten in ISO-8859-1 übertragen
            $input = $request->all();
            foreach ($input as $key => $value) {
                $input[$key] = mb_convert_encoding("$value", "UTF-8", "ISO-8859-1");
            }
            $request->replace($input);
        }
        $this->url = $request->url();
        # Zunächst überprüfen wir die eingegebenen Einstellungen:
        # Fokus
        $this->fokus = $request->input('focus', 'web');
        # Suma-File
        if (App::isLocale("en")) {
            $this->sumaFile = config_path() . "/sumas.xml";
        } else {
            $this->sumaFile = config_path() . "/sumas.xml";
        }
        if (!file_exists($this->sumaFile)) {
            die(trans('metaGer.formdata.cantLoad'));
        }
        # Sucheingabe
        $this->eingabe = trim($request->input('eingabe', ''));
        $this->q       = mb_strtolower($this->eingabe, "UTF-8");
        # IP
        $this->ip = $request->ip();
        # Unser erster Schritt wird sein, IP-Adresse und USER-Agent zu anonymisieren, damit
        # nicht einmal wir selbst noch Zugriff auf die Daten haben:
        $this->ip = preg_replace("/(\d+)\.(\d+)\.\d+.\d+/s", "$1.$2.0.0", $this->ip);

        # Language
        if (isset($_SERVER['HTTP_LANGUAGE'])) {
            $this->language = $_SERVER['HTTP_LANGUAGE'];
        } else {
            $this->language = "";
        }
        # Category
        $this->category = $request->input('category', '');
        # Request Times
        $this->time = $request->input('time', 1000);
        # Page
        $this->page = 1;
        # Lang
        $this->lang = $request->input('lang', 'all');
        if ($this->lang !== "de" && $this->lang !== "en" && $this->lang !== "all") {
            $this->lang = "all";
        }

        $this->agent  = new Agent();
        $this->mobile = $this->agent->isMobile();
        # Sprüche
        $this->sprueche = $request->input('sprueche', 'on');
        if ($this->sprueche === "on") {
            $this->sprueche = true;
        } else {
            $this->sprueche = false;
        }
        $this->maps = $request->input('maps', 'off');
        if ($this->maps === "on") {
            $this->maps = true;
        } else {
            $this->maps = false;
        }
        $this->newtab = $request->input('newtab', 'on');
        if ($this->newtab === "on") {
            $this->newtab = "_blank";
        } else {
            $this->newtab = "_self";
        }
        # Theme
        $this->theme = preg_replace("/[^[:alnum:][:space:]]/u", '', $request->input('theme', 'default'));
        # Ergebnisse pro Seite:
        $this->resultCount = $request->input('resultCount', '20');
        # Manchmal müssen wir Parameter anpassen um den Sucheinstellungen gerecht zu werden:
        if ($request->has('dart')) {
            $this->time       = 10000;
            $this->warnings[] = trans('metaGer.formdata.dartEurope');
        }
        if ($this->time <= 500 || $this->time > 20000) {
            $this->time = 1000;
        }
        if ($request->has('minism') && ($request->has('fportal') || $request->has('harvest'))) {
            $input    = $request->all();
            $newInput = [];
            foreach ($input as $key => $value) {
                if ($key !== "fportal" && $key !== "harvest") {
                    $newInput[$key] = $value;
                }
            }
            $request->replace($newInput);
        }
        if (App::isLocale("en")) {
            $this->sprueche = "off";
        }
        if ($this->resultCount <= 0 || $this->resultCount > 200) {
            $this->resultCount = 1000;
        }
        if ($request->has('onenewspageAll') || $request->has('onenewspageGermanyAll')) {
            $this->time  = 5000;
            $this->cache = "cache";
        }
        if ($request->has('password')) {
            $this->password = $request->input('password');
        }
        if ($request->has('quicktips')) {
            $this->quicktips = false;
        } else {
            $this->quicktips = true;
        }
        $this->out = $request->input('out', "html");
        # Standard output format html
        if ($this->out !== "html" && $this->out !== "json" && $this->out !== "results" && $this->out !== "results-with-style" && $this->out !== "result-count" && $this->out !== "rss20") {
            $this->out = "html";
        }
        # Wir schalten den Cache aus, wenn die Ergebniszahl überprüft werden soll
        #   => out=result-count
        # Ist dieser Parameter gesetzt, so soll überprüft werden, wie viele Ergebnisse wir liefern.
        # Wenn wir gecachte Ergebnisse zurück liefern würden, wäre das nicht sonderlich klug, da es dann keine Aussagekraft hätte
        # ob MetaGer funktioniert (bzw. die Fetcher laufen)
        # Auch ein Log sollte nicht geschrieben werden, da es am Ende ziemlich viele Logs werden könnten.
        if ($this->out === "result-count") {
            $this->canCache  = false;
            $this->shouldLog = false;
        } else {
            $this->shouldLog = true;
        }
    }

    public function checkSpecialSearches(Request $request)
    {
        if ($request->has('site')) {
            $site = $request->input('site');
        } else {
            $site = "";
        }

        $this->searchCheckSitesearch($site);
        $this->searchCheckHostBlacklist();
        $this->searchCheckDomainBlacklist();
        $this->searchCheckPhrase();
        $this->searchCheckStopwords();
        $this->searchCheckNoSearch();
    }

    public function searchCheckSitesearch($site)
    {
        // matches '[... ]site:test.de[ ...]'
        while (preg_match("/(^|.+\s)site:(\S+)(?:\s(.+)|($))/si", $this->q, $match)) {
            $this->site = $match[2];
            $this->q    = $match[1] . $match[3];
        }
        if ($site !== "") {
            $this->site = $site;
        }
    }

    public function searchCheckHostBlacklist()
    {
        // matches '[... ]-site:test.de[ ...]'
        while (preg_match("/(^|.+\s)-site:([^\s\*]\S*)(?:\s(.+)|($))/si", $this->q, $match)) {
            $this->hostBlacklist[] = $match[2];
            $this->q               = $match[1] . $match[3];
        }
        if (sizeof($this->hostBlacklist) > 0) {
            $hostString = "";
            foreach ($this->hostBlacklist as $host) {
                $hostString .= $host . ", ";
            }
            $hostString       = rtrim($hostString, ", ");
            $this->warnings[] = trans('metaGer.formdata.hostBlacklist', ['host' => $hostString]);
        }
    }

    public function searchCheckDomainBlacklist()
    {
        // matches '[... ]-site:*.test.de[ ...]'
        while (preg_match("/(^|.+\s)-site:\*\.(\S+)(?:\s(.+)|($))/si", $this->q, $match)) {
            $this->domainBlacklist[] = $match[2];
            $this->q                 = $match[1] . $match[3];
        }
        if (sizeof($this->domainBlacklist) > 0) {
            $domainString = "";
            foreach ($this->domainBlacklist as $domain) {
                $domainString .= $domain . ", ";
            }
            $domainString     = rtrim($domainString, ", ");
            $this->warnings[] = trans('metaGer.formdata.domainBlacklist', ['domain' => $domainString]);
        }
    }

    public function searchCheckStopwords()
    {
        // matches '[... ]-test[ ...]'
        while (preg_match("/(^|.+\s)-(\S+)(?:\s(.+)|($))/si", $this->q, $match)) {
            $this->stopWords[] = $match[2];
            $this->q           = $match[1] . $match[3];
        }
        if (sizeof($this->stopWords) > 0) {
            $stopwordsString = "";
            foreach ($this->stopWords as $stopword) {
                $stopwordsString .= $stopword . ", ";
            }
            $stopwordsString  = rtrim($stopwordsString, ", ");
            $this->warnings[] = trans('metaGer.formdata.stopwords', ['stopwords' => $stopwordsString]);
        }
    }

    public function searchCheckPhrase()
    {
        $p   = "";
        $tmp = $this->q;
        // matches '[... ]"test satz"[ ...]'
        while (preg_match("/(^|.+\s)\"(.+)\"(?:\s(.+)|($))/si", $tmp, $match)) {
            $tmp             = $match[1] . $match[3];
            $this->phrases[] = strtolower($match[2]);
        }
        foreach ($this->phrases as $phrase) {
            $p .= "\"$phrase\", ";
        }
        $p = rtrim($p, ", ");
        if (sizeof($this->phrases) > 0) {
            $this->warnings[] = trans('metaGer.formdata.phrase', ['phrase' => $p]);
        }
    }

    public function searchCheckNoSearch()
    {
        if ($this->q === "") {
            $this->warnings[] = trans('metaGer.formdata.noSearch');
        }
    }

    public function nextSearchLink()
    {
        if (isset($this->next) && isset($this->next['engines']) && count($this->next['engines']) > 0) {
            $requestData = $this->request->except(['page', 'out']);
            if ($this->request->input('out', '') !== "results" && $this->request->input('out', '') !== '') {
                $requestData["out"] = $this->request->input('out');
            }
            $requestData['next'] = md5(serialize($this->next));
            $link                = action('MetaGerSearch@search', $requestData);
        } else {
            $link = "#";
        }
        return $link;
    }

    public function rankAll()
    {
        foreach ($this->engines as $engine) {
            $engine->rank($this->getQ());
        }
    }

# Hilfsfunktionen

    public function removeInvalids()
    {
        $results = [];
        foreach ($this->results as $result) {
            if ($result->isValid($this)) {
                $results[] = $result;
            }

        }
    }

    public function showQuicktips()
    {
        return $this->quicktips;
    }

    public function popAd()
    {
        if (count($this->ads) > 0) {
            return get_object_vars(array_shift($this->ads));
        } else {
            return null;
        }
    }

    public function hasProducts()
    {
        if (count($this->products) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getProducts()
    {
        $return = [];
        foreach ($this->products as $product) {
            $return[] = get_object_vars($product);
        }
        return $return;
    }

    public function canCache()
    {
        return $this->canCache;
    }

    public function createLogs()
    {
        if ($this->shouldLog) {
            $redis = Redis::connection('redisLogs');
            try
            {
                $logEntry = "";
                $logEntry .= "[" . date("D M d H:i:s") . "]";
                /*
                Someone might wonder now why we are saving the IP-Adress to the log file here:
                It's because we were targets of heavy Bot attacks which created so many Search-Request to our Servers that
                not only our servers but the ones from some of our search engines too collapsed.
                At that point we could'nt prevent the Bot from accessing our server because we would need it's IP-Adress to do so.

                That's why we need to save the IP-Adress to our Log-Files temporarily. The logrotate process that shifts our Log-Files will then
                automatically remove the IP-Adresses from the Log-File after a few hours.
                This method gives us just enough time to prevent malicious Software from bringing our servers down and at the same time having not a single
                IP-Adress older than one day stored on our servers. (Except the ones who got banned in that short period of course) ;-)
                 */
                $logEntry .= " ip=" . $this->request->ip();
                $logEntry .= " pid=" . getmypid();
                $logEntry .= " ref=" . $this->request->header('Referer');
                $logEntry .= " time=" . round((microtime(true) - $this->starttime), 2) . " serv=" . $this->fokus;
                $logEntry .= " interface=" . LaravelLocalization::getCurrentLocale();
                $logEntry .= " sprachfilter=" . $this->lang;
                $logEntry .= " search=" . $this->eingabe;

                # 2 Arten von Logs in einem wird die Anzahl der Abfragen an eine Suchmaschine gespeichert und in der anderen
                # die Anzahl, wie häufig diese Ergebnisse geliefert hat.
                $enginesToLoad = $this->enginesToLoad;
                $redis->pipeline(function ($pipe) use ($enginesToLoad, $logEntry) {
                    $pipe->rpush('logs.search', $logEntry);
                    foreach ($this->enginesToLoad as $name => $answered) {
                        $pipe->incr('logs.engines.requests.' . $name);
                        if ($answered) {
                            $pipe->incr('logs.engines.answered.' . $name);
                        }
                    }
                });
            } catch (\Exception $e) {
                return;
            }
        }
    }

    public function addLink($link)
    {
        if (strpos($link, "http://") === 0) {
            $link = substr($link, 7);
        }

        if (strpos($link, "https://") === 0) {
            $link = substr($link, 8);
        }

        if (strpos($link, "www.") === 0) {
            $link = substr($link, 4);
        }

        $link = trim($link, "/");
        $hash = md5($link);
        if (isset($this->addedLinks[$hash])) {
            return false;
        } else {
            $this->addedLinks[$hash] = 1;
            return true;
        }
    }

    public function addHostCount($host)
    {
        $hash = md5($host);
        if (isset($this->addedHosts[$hash])) {
            $this->addedHosts[$hash] += 1;
        } else {
            $this->addedHosts[$hash] = 1;
        }
    }

# Generators

    public function generateSearchLink($fokus, $results = true)
    {
        $requestData          = $this->request->except(['page', 'next']);
        $requestData['focus'] = $fokus;
        if ($results) {
            $requestData['out'] = "results";
        } else {
            $requestData['out'] = "";
        }

        $link = action('MetaGerSearch@search', $requestData);
        return $link;
    }

    public function generateQuicktipLink()
    {
        $link = action('MetaGerSearch@quicktips');

        return $link;
    }

    public function generateSiteSearchLink($host)
    {
        $host        = urlencode($host);
        $requestData = $this->request->except(['page', 'out', 'next']);
        $requestData['eingabe'] .= " site:$host";
        $requestData['focus'] = "web";
        $link                 = action('MetaGerSearch@search', $requestData);
        return $link;
    }

    public function generateRemovedHostLink($host)
    {
        $host        = urlencode($host);
        $requestData = $this->request->except(['page', 'out', 'next']);
        $requestData['eingabe'] .= " -site:$host";
        $link = action('MetaGerSearch@search', $requestData);
        return $link;
    }

    public function generateRemovedDomainLink($domain)
    {
        $domain      = urlencode($domain);
        $requestData = $this->request->except(['page', 'out', 'next']);
        $requestData['eingabe'] .= " -site:*.$domain";
        $link = action('MetaGerSearch@search', $requestData);
        return $link;
    }

    public function getUnFilteredLink()
    {
        $requestData         = $this->request->except(['lang']);
        $requestData['lang'] = "all";
        $link                = action('MetaGerSearch@search', $requestData);
        return $link;
    }

# Komplexe Getter

    public function getHostCount($host)
    {
        $hash = md5($host);
        if (isset($this->addedHosts[$hash])) {
            return $this->addedHosts[$hash];
        } else {
            return 0;
        }
    }

    public function getImageProxyLink($link)
    {
        $requestData        = [];
        $requestData["url"] = $link;
        $link               = action('Pictureproxy@get', $requestData);
        return $link;
    }

    public function getHashCode()
    {
        $string = url()->full();
        return md5($string);
    }

# Einfache Getter

    public function getSite()
    {
        return $this->site;
    }

    public function getNewtab()
    {
        return $this->newtab;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function getFokus()
    {
        return $this->fokus;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getEingabe()
    {
        return $this->eingabe;
    }

    public function getQ()
    {
        return $this->q;
    }

    public function getUrl()
    {
        return $this->url;
    }
    public function getTime()
    {
        return $this->time;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function getSprueche()
    {
        return $this->sprueche;
    }

    public function getMaps()
    {
        return $this->maps;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getPhrases()
    {
        return $this->phrases;
    }
    public function getPage()
    {
        return $this->page;
    }

    public function getSumaFile()
    {
        return $this->sumaFile;
    }

    public function getUserHostBlacklist()
    {
        return $this->hostBlacklist;
    }

    public function getUserDomainBlacklist()
    {
        return $this->domainBlacklist;
    }

    public function getDomainBlacklist()
    {
        return $this->domainsBlacklisted;
    }

    public function getUrlBlacklist()
    {
        return $this->urlsBlacklisted;
    }

    public function getLanguageDetect()
    {
        return $this->languageDetect;
    }

    public function getStopWords()
    {
        return $this->stopWords;
    }

    public function getStartCount()
    {
        return $this->startCount;
    }
}
