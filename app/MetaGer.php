<?php
namespace App;

use App;
use App\lib\TextLanguageDetect\TextLanguageDetect;
use Cache;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use LaravelLocalization;
use Log;
use Predis\Connection\ConnectionException;
use Redis;

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
            Log::warning(trans('metaGer.blacklist.failed'));
        }

        # Parser Skripte einhängen
        $dir = app_path() . "/Models/parserSkripte/";
        foreach (scandir($dir) as $filename) {
            $path = $dir . $filename;
            if (is_file($path)) {
                require $path;
            }
        }

        # Spracherkennung starten
        $this->languageDetect = new TextLanguageDetect();
        $this->languageDetect->setNameMode("2");

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

    public function removeInvalids()
    {
        $results = [];
        foreach ($this->results as $result) {
            if ($result->isValid($this)) {
                $results[] = $result;
            }

        }
    }

    public function combineResults()
    {
        foreach ($this->engines as $engine) {
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
        }

        uasort($this->results, function ($a, $b) {
            if ($a->getRank() == $b->getRank()) {
                return 0;
            }

            return ($a->getRank() < $b->getRank()) ? 1 : -1;
        });

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
            $password = getenv('mainz');
            $eingabe  = $this->eingabe;
            $password = md5($eingabe . $password);
            if ($this->password === $password) {
                $this->ads       = [];
                $this->validated = true;
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

    public function createSearchEngines(Request $request)
    {
        if (!$request->has("eingabe")) {
            return;
        }

        # Überprüfe, welche Sumas eingeschaltet sind
        $xml                  = simplexml_load_file($this->sumaFile);
        $enabledSearchengines = [];
        $overtureEnabled      = false;
        $sumaCount            = 0;
        $sumas                = $xml->xpath("suma");

        foreach ($sumas as $suma) {
            if ($this->fokus === "angepasst") {
                if ($request->has($suma["name"])
                    || ($this->fokus !== "bilder"
                        && ($suma["name"]->__toString() === "qualigo"
                            || $suma["name"]->__toString() === "similar_product_ads"
                            || (!$overtureEnabled && $suma["name"]->__toString() === "overtureAds")
                        )
                    )
                ) {

                    if (!(isset($suma['disabled']) && $suma['disabled']->__toString() === "1")) {
                        if ($suma["name"]->__toString() === "overture" || $suma["name"]->__toString() === "overtureAds") {
                            $overtureEnabled = true;
                        }
                        if ($suma["name"]->__toString() !== "qualigo" && $suma["name"]->__toString() !== "similar_product_ads" && $suma["name"]->__toString() !== "overtureAds") {
                            $sumaCount += 1;
                        }
                        $enabledSearchengines[] = $suma;
                    }
                }
            } else {
                $types = explode(",", $suma["type"]);
                if (in_array($this->fokus, $types)
                    || ($this->fokus !== "bilder"
                        && ($suma["name"]->__toString() === "qualigo"
                            || $suma["name"]->__toString() === "similar_product_ads"
                            || (!$overtureEnabled && $suma["name"]->__toString() === "overtureAds")
                        )
                    )
                ) {
                    if (!(isset($suma['disabled']) && $suma['disabled']->__toString() === "1")) {
                        if ($suma["name"]->__toString() === "overture" || $suma["name"]->__toString() === "overtureAds") {
                            $overtureEnabled = true;
                        }
                        if ($suma["name"]->__toString() !== "qualigo" && $suma["name"]->__toString() !== "similar_product_ads" && $suma["name"]->__toString() !== "overtureAds") {
                            $sumaCount += 1;
                        }
                        $enabledSearchengines[] = $suma;
                    }
                }
            }
        }

        # Sonderregelung für alle Suchmaschinen, die zu den Minisuchern gehören. Diese können alle gemeinsam über einen Link abgefragt werden
        $subcollections = [];
        $tmp            = [];
        foreach ($enabledSearchengines as $engine) {
            if (isset($engine['minismCollection'])) {
                $subcollections[] = $engine['minismCollection']->__toString();
            } else {
                $tmp[] = $engine;
            }

        }
        $enabledSearchengines = $tmp;
        if (sizeof($subcollections) > 0) {
            $count                        = sizeof($subcollections) * 10;
            $minisucherEngine             = $xml->xpath('suma[@name="minism"]')[0];
            $subcollections               = urlencode("(" . implode(" OR ", $subcollections) . ")");
            $minisucherEngine["formData"] = str_replace("<<SUBCOLLECTIONS>>", $subcollections, $minisucherEngine["formData"]);
            $minisucherEngine["formData"] = str_replace("<<COUNT>>", $count, $minisucherEngine["formData"]);
            $enabledSearchengines[]       = $minisucherEngine;
        }

        if ($sumaCount <= 0) {
            $this->errors[] = trans('metaGer.settings.noneSelected');
        }
        $engines = [];

        # Wenn eine Sitesearch durchgeführt werden soll, überprüfen wir ob eine der Suchmaschinen überhaupt eine Sitesearch unterstützt
        $siteSearchFailed = $this->checkCanNotSitesearch($enabledSearchengines);

        $typeslist = [];
        $counter   = 0;

        if ($request->has('next') && Cache::has($request->input('next')) && unserialize(Cache::get($request->input('next')))['page'] > 1) {
            $next       = unserialize(Cache::get($request->input('next')));
            $this->page = $next['page'];
            $engines    = $next['engines'];
            if (isset($next['startForwards'])) {
                $this->startForwards = $next['startForwards'];
            }

            if (isset($next['startBackwards'])) {
                $this->startBackwards = $next['startBackwards'];
            }

        } else {
            foreach ($enabledSearchengines as $engine) {

                if (!$siteSearchFailed && strlen($this->site) > 0 && (!isset($engine['hasSiteSearch']) || $engine['hasSiteSearch']->__toString() === "0")) {

                    continue;
                }
                # Wenn diese Suchmaschine gar nicht eingeschaltet sein soll
                $path = "App\Models\parserSkripte\\" . ucfirst($engine["package"]->__toString());

                if (!file_exists(app_path() . "/Models/parserSkripte/" . ucfirst($engine["package"]->__toString()) . ".php")) {
                    Log::error(trans('metaGer.engines.noParser', ['engine' => $engine["name"]]));
                    continue;
                }

                $time = microtime();

                try
                {
                    $tmp = new $path($engine, $this);
                } catch (\ErrorException $e) {
                    Log::error(trans('metaGer.engines.cantQuery', ['engine' => $engine["name"], 'error' => var_dump($e)]));
                    continue;
                }

                if ($tmp->enabled && isset($this->debug)) {
                    $this->warnings[] = $tmp->service . "   Connection_Time: " . $tmp->connection_time . "    Write_Time: " . $tmp->write_time . " Insgesamt:" . ((microtime() - $time) / 1000);
                }

                if ($tmp->isEnabled()) {
                    $engines[] = $tmp;
                }

            }
        }

        # Wir starten die Suche manuell:
        foreach ($engines as $engine) {
            $engine->startSearch($this);
        }

        $this->adjustFocus($sumas, $enabledSearchengines);

        /* Nun passiert ein elementarer Schritt.
         * Wir warten auf die Antwort der Suchmaschinen, da wir vorher nicht weiter machen können.
         * Aber natürlich nicht ewig.
         * Die Verbindung steht zu diesem Zeitpunkt und auch unsere Request wurde schon gesendet.
         * Wir geben der Suchmaschine nun bis zu 500ms Zeit zu antworten.
         */

        # Wir zählen die Suchmaschinen, die durch den Cache beantwortet wurden:
        $enginesToLoad = 0;
        $canBreak      = false;
        foreach ($engines as $engine) {
            if ($engine->cached) {
                $enginesToLoad--;
                if ($overtureEnabled && ($engine->name === "overture" || $engine->name === "overtureAds")) {
                    $canBreak = true;
                }
            }
        }

        $enginesToLoad += count($engines);

        $this->waitForResults($enginesToLoad, $overtureEnabled, $canBreak);

        $this->retrieveResults($engines);
    }

    public function adjustFocus($sumas, $enabledSearchengines)
    {
        # Jetzt werden noch alle Kategorien der Settings durchgegangen und die jeweils enthaltenen namen der Suchmaschinen gespeichert.
        $foki = [];
        foreach ($sumas as $suma) {
            if ((!isset($suma['disabled']) || $suma['disabled'] === "") && (!isset($suma['userSelectable']) || $suma['userSelectable']->__toString() === "1")) {
                if (isset($suma['type'])) {
                    $f = explode(",", $suma['type']->__toString());
                    foreach ($f as $tmp) {
                        $name                                    = $suma['name']->__toString();
                        $foki[$tmp][$suma['name']->__toString()] = $name;
                    }
                } else {
                    $name                                        = $suma['name']->__toString();
                    $foki["andere"][$suma['name']->__toString()] = $name;
                }
            }
        }

        # Es werden auch die Namen der aktuell aktiven Suchmaschinen abgespeichert.
        $realEngNames = [];
        foreach ($enabledSearchengines as $realEng) {
            $nam = $realEng["name"]->__toString();
            if ($nam !== "qualigo" && $nam !== "overtureAds") {
                $realEngNames[] = $nam;
            }
        }

        # Anschließend werden diese beiden Listen verglichen (jeweils eine der Fokuslisten für jeden Fokus), um herauszufinden ob sie vielleicht identisch sind. Ist dies der Fall, so hat der Nutzer anscheinend Suchmaschinen eines kompletten Fokus eingestellt. Der Fokus wird dementsprechend angepasst.
        foreach ($foki as $fok => $engs) {
            $isFokus      = true;
            $fokiEngNames = [];
            foreach ($engs as $eng) {
                $fokiEngNames[] = $eng;
            }
            foreach ($fokiEngNames as $fen) {
                if (!in_array($fen, $realEngNames)) {
                    $isFokus = false;
                }
            }
            foreach ($realEngNames as $ren) {
                if (!in_array($ren, $fokiEngNames)) {
                    $isFokus = false;
                }
            }
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
    }

    public function waitForResults($enginesToLoad, $overtureEnabled, $canBreak)
    {
        $loadedEngines = 0;
        $timeStart     = microtime(true);
        while (true) {
            $time          = (microtime(true) - $timeStart) * 1000;
            $loadedEngines = intval(Redis::hlen('search.' . $this->getHashCode()));
            if ($overtureEnabled && (Redis::hexists('search.' . $this->getHashCode(), 'overture') || Redis::hexists('search.' . $this->getHashCode(), 'overtureAds'))) {
                $canBreak = true;
            }

            # Abbruchbedingung
            if ($time < 500) {
                if (($enginesToLoad === 0 || $loadedEngines >= $enginesToLoad) && $canBreak) {
                    break;
                }

            } elseif ($time >= 500 && $time < $this->time) {
                if (($enginesToLoad === 0 || ($loadedEngines / ($enginesToLoad * 1.0)) >= 0.8) && $canBreak) {
                    break;
                }

            } else {
                break;
            }
            usleep(50000);
        }
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

    public function parseFormData(Request $request)
    {
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
        if (strlen($this->eingabe) === 0) {
            $this->warnings[] = trans('metaGer.formdata.noSearch');
        }
        $this->q = $this->eingabe;
        # IP
        $this->ip = $request->ip();
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
        $this->sprueche = $request->input('sprueche', 'off');
        if ($this->sprueche === "off") {
            $this->sprueche = true;
        } else {
            $this->sprueche = false;
        }
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
        if ($request->has('tab')) {
            if ($request->input('tab') === "off") {
                $this->tab = "_blank";
            } else {
                $this->tab = "_self";
            }
        } else {
            $this->tab = "_blank";
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
        if ($this->out !== "html" && $this->out !== "json" && $this->out !== "results" && $this->out !== "results-with-style") {
            $this->out = "html";
        }
        $this->request = $request;
    }

    public function checkSpecialSearches(Request $request)
    {
        # Site Search
        if (preg_match("/(.*)\bsite:(\S+)(.*)/si", $this->q, $match)) {
            $this->site = $match[2];
            $this->q    = $match[1] . $match[3];
        }
        if ($request->has('site')) {
            $this->site = $request->input('site');
        }

        # Host Blacklisting
        # Wenn die Suchanfrage um das Schlüsselwort "-host:*" ergänzt ist, sollen bestimmte Hosts nicht eingeblendet werden
        while (preg_match("/(.*)(^|\s)-host:(\S+)(.*)/si", $this->q, $match)) {
            $this->hostBlacklist[] = $match[3];
            $this->q               = $match[1] . $match[4];
        }
        if (sizeof($this->hostBlacklist) > 0) {
            $hostString = "";
            foreach ($this->hostBlacklist as $host) {
                $hostString .= $host . ", ";
            }
            $hostString       = rtrim($hostString, ", ");
            $this->warnings[] = trans('metaGer.formdata.hostBlacklist', ['host' => $hostString]);
        }

        # Domain Blacklisting
        # Wenn die Suchanfrage um das Schlüsselwort "-domain:*" ergänzt ist, sollen bestimmte Domains nicht eingeblendet werden
        while (preg_match("/(.*)(^|\s)-domain:(\S+)(.*)/si", $this->q, $match)) {
            $this->domainBlacklist[] = $match[3];
            $this->q                 = $match[1] . $match[4];
        }
        if (sizeof($this->domainBlacklist) > 0) {
            $domainString = "";
            foreach ($this->domainBlacklist as $domain) {
                $domainString .= $domain . ", ";
            }
            $domainString     = rtrim($domainString, ", ");
            $this->warnings[] = trans('metaGer.formdata.domainBlacklist', ['domain' => $domainString]);
        }

        # Stopwords
        # Alle mit "-" gepräfixten Worte sollen aus der Suche ausgeschlossen werden.
        while (preg_match("/(.*)(^|\s)-(\S+)(.*)/si", $this->q, $match)) {
            $this->stopWords[] = $match[3];
            $this->q           = $match[1] . $match[4];
        }
        if (sizeof($this->stopWords) > 0) {
            $stopwordsString = "";
            foreach ($this->stopWords as $stopword) {
                $stopwordsString .= $stopword . ", ";
            }
            $stopwordsString  = rtrim($stopwordsString, ", ");
            $this->warnings[] = trans('metaGer.formdata.stopwords', ['stopwords' => $stopwordsString]);
        }

        # Phrasensuche
        $p   = "";
        $tmp = $this->q;
        while (preg_match("/(.*)\"(.+)\"(.*)/si", $tmp, $match)) {
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

    public function nextSearchLink()
    {
        if (isset($this->next) && isset($this->next['engines']) && count($this->next['engines']) > 0) {
            $requestData         = $this->request->except(['page', 'out']);
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
            $engine->rank($this);
        }
    }

# Hilfsfunktionen

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

    public function canCache()
    {
        return $this->canCache;
    }

    public function createLogs()
    {
        $redis = Redis::connection('redisLogs');
        try
        {
            $logEntry = "";
            $logEntry .= "[" . date(DATE_RFC822, mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))) . "]";
            $logEntry .= " pid=" . getmypid();
            $logEntry .= " ref=" . $this->request->header('Referer');
            $useragent = $this->request->header('User-Agent');
            $useragent = str_replace("(", " ", $useragent);
            $useragent = str_replace(")", " ", $useragent);
            $useragent = str_replace(" ", "", $useragent);
            $logEntry .= " time=" . round((microtime(true) - $this->starttime), 2) . " serv=" . $this->fokus;
            $logEntry .= " search=" . $this->eingabe;
            $redis->rpush('logs.search', $logEntry);
        } catch (\Exception $e) {
            return;
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
        $requestData['eingabe'] .= " -host:$host";
        $link = action('MetaGerSearch@search', $requestData);
        return $link;
    }

    public function generateRemovedDomainLink($domain)
    {
        $domain      = urlencode($domain);
        $requestData = $this->request->except(['page', 'out', 'next']);
        $requestData['eingabe'] .= " -domain:$domain";
        $link = action('MetaGerSearch@search', $requestData);
        return $link;
    }

# Komplexe Getter

    public function getHostCount($host)
    {
        if (isset($this->addedHosts[$host])) {
            return $this->addedHosts[$host];
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

    public function getTab()
    {
        return $this->tab;
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
