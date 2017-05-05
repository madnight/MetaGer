<?php

namespace App\Models;

use App\Jobs\Searcher;
use App\MetaGer;
use Cache;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Log;
use Illuminate\Support\Facades\Redis;

abstract class Searchengine
{
    use DispatchesJobs;

    public $ch; # Curl Handle zum erhalten der Ergebnisse
    public $getString = ""; # Der String für die Get-Anfrage
    public $engine; # Die ursprüngliche Engine XML
    public $enabled  = true; # true, wenn die Suchmaschine nicht explizit disabled ist
    public $results  = []; # Die geladenen Ergebnisse
    public $ads      = []; # Die geladenen Werbungen
    public $products = []; # Die geladenen Produkte
    public $loaded   = false; # wahr, sobald die Ergebnisse geladen wurden
    public $cached   = false;

    public $ip; # Die IP aus der metager
    public $gefVon; # Der HTML-Code für die Verlinkung des Suchanbieters
    public $uses; # Die Anzahl der Nutzungen dieser Suchmaschine
    public $homepage; # Die Homepage dieser Suchmaschine
    public $name; # Der Name dieser Suchmaschine
    public $disabled; # Ob diese Suchmaschine ausgeschaltet ist
    public $useragent; # Der HTTP Useragent
    public $startTime; # Die Zeit der Erstellung dieser Suchmaschine
    public $hash; # Der Hash-Wert dieser Suchmaschine

    public $fp; # Wird für Artefakte benötigt
    public $socketNumber    = null; # Wird für Artefakte benötigt
    public $counter         = 0; # Wird eventuell für Artefakte benötigt
    public $write_time      = 0; # Wird eventuell für Artefakte benötigt
    public $connection_time = 0; # Wird eventuell für Artefakte benötigt

    public function __construct(\SimpleXMLElement $engine, MetaGer $metager)
    {
        # Versucht möglichst viele attribute aus dem engine XML zu laden
        foreach ($engine->attributes() as $key => $value) {
            $this->$key = $value->__toString();
        }

        # Standardhomepage metager.de
        if (!isset($this->homepage)) {
            $this->homepage = "https://metager.de";
        }

        # Speichert die XML der Engine
        $this->engine = $engine->asXML();

        # Cache Standarddauer 60
        if (!isset($this->cacheDuration)) {
            $this->cacheDuration = 60;
        }

        $this->enabled = true;

        # Eine Suchmaschine kann automatisch temporär deaktiviert werden, wenn es Verbindungsprobleme gab:
        if (isset($this->disabled) && strtotime($this->disabled) <= time()) {
            # In diesem Fall ist der Timeout der Suchmaschine abgelaufen.
            $this->enable($metager->getSumaFile(), "Die Suchmaschine " . $this->name . " wurde wieder eingeschaltet.");
        } elseif (isset($this->disabled) && strtotime($this->disabled) > time()) {
            $this->enabled = false;
            return;
        }

        $this->useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1";
        $this->ip        = $metager->getIp();
        $this->gefVon    = "<a href=\"" . $this->homepage . "\" target=\"_blank\" rel=\"noopener\">" . $this->displayName . "</a>";
        $this->startTime = microtime();

        # Suchstring generieren
        $q = "";
        if (isset($this->hasSiteSearch) && $this->hasSiteSearch === "1") {
            if (strlen($metager->getSite()) === 0) {
                $q = $metager->getQ();
            } else {
                $q = $metager->getQ() . " site:" . $metager->getSite();
            }

        } else {
            $q = $metager->getQ();
        }
        $this->getString  = $this->generateGetString($q, $metager->getUrl(), $metager->getLanguage(), $metager->getCategory());
        $this->hash       = md5($this->host . $this->getString . $this->port . $this->name);
        $this->resultHash = $metager->getHashCode();
        $this->canCache   = $metager->canCache();
        if (!isset($this->additionalHeaders)) {$this->additionalHeaders = "";}
    }

    abstract public function loadResults($result);

    # ???
    public function getNext(MetaGer $metager, $result)
    {

    }

    # Prüft, ob die Suche bereits gecached ist, ansonsted wird sie als Job dispatched
    public function startSearch(\App\MetaGer $metager)
    {
        if ($this->canCache && Cache::has($this->hash) && 0 == 1) {
            $this->cached = true;
            $this->retrieveResults($metager);
        } else {
            // We will push the confirmation of the submission to the Result Hash
            Redis::hset('search.' . $this->resultHash, $this->name, "waiting");
            // We need to submit a action that one of our workers can understand
            // The missions are submitted to a redis queue in the following string format
            // <ResultHash>;<URL to fetch>
            // With <ResultHash> being the Hash Value where the fetcher will store the result.
            // and <URL to fetch> being the full URL to the searchengine
            $url = "";
            if($this->port === "443"){
                $url = "https://";
            }else{
                $url = "http://";
            }
            $url .= $this->host . $this->getString;
            $mission = $this->resultHash . ";" . $url;
            // Submit this mission to the corresponding Redis Queue
            // Since each Searcher is dedicated to one specific search engine
            // each Searcher has it's own queue lying under the redis key <name>.queue
            Redis::rpush($this->name . ".queue", $mission);

            // If there is no Searcher process for this engine running at this time, we start one
            if(Redis::get($this->name) === NULL){
                Log::info("Starting Searcher");
                /* Die Anfragen an die Suchmaschinen werden nun von der Laravel-Queue bearbeitet:
                 *  Hinweis: solange in der .env der QUEUE_DRIVER auf "sync" gestellt ist, werden die Abfragen
                 *  nacheinander abgeschickt.
                 *  Sollen diese Parallel verarbeitet werden, muss ein anderer QUEUE_DRIVER verwendet werden.
                 *  siehe auch: https://laravel.com/docs/5.2/queues
                 */
                $this->dispatch(new Searcher($this->name));
            }
        }
    }

    # Ruft die Ranking-Funktion aller Ergebnisse auf.
    public function rank($eingabe)
    {
        foreach ($this->results as $result) {
            $result->rank($eingabe);
        }
    }

    # Magic ???
    private function setStatistic($key, $val)
    {

        $oldVal = floatval(Redis::hget($this->name, $key)) * $this->uses;
        $newVal = ($oldVal + max($val, 0)) / $this->uses;
        Redis::hset($this->name, $key, $newVal);
        $this->$key = $newVal;
    }

    # Entfernt wenn gesetzt das disabled="1" für diese Suchmaschine aus der sumas.xml
    public function enable($sumaFile, $message)
    {
        Log::info($message);
        $xml = simplexml_load_file($sumaFile);
        unset($xml->xpath("//sumas/suma[@name='" . $this->name . "']")['0']['disabled']);
        $xml->saveXML($sumaFile);
        $this->enabled = true;
    }

    public function closeFp()
    {
        fclose($this->fp);
    }

    # Öffnet einen neuen Socket für diese Engine
    public function getSocket()
    {
        $number = Redis::hget('search.' . $this->hash, $this->name);
        if ($number === null) {
            die("test");
            return null;
        } else {
            return pfsockopen($this->getHost() . ":" . $this->port . "/$number", $this->port, $errstr, $errno, 1);
        }
    }

    # Fragt die Ergebnisse von Redis ab und lädt Sie
    public function retrieveResults(MetaGer $metager)
    {
        if ($this->loaded) {
            return true;
        }

        $body = "";
        if ($this->canCache && $this->cacheDuration > 0 && Cache::has($this->hash) && 0 === 1) {
            $body = Cache::get($this->hash);
        } elseif (Redis::hexists('search.' . $this->resultHash, $this->name)) {
            $body = Redis::hget('search.' . $this->resultHash, $this->name);
            if ($this->canCache && $this->cacheDuration > 0 && 0 === 1) {
                Cache::put($this->hash, $body, $this->cacheDuration);
            }

        }
        if ($body !== "") {
            $this->loadResults($body);
            $this->getNext($metager, $body);
            $this->loaded = true;
            Redis::hdel('search.' . $this->hash, $this->name);
            return true;
        } else {
            return false;
        }
    }

    public function shutdown()
    {
        Redis::del($this->host . "." . $this->socketNumber);
    }

    # Erstellt den für die Get-Anfrage genutzten Host-Link
    protected function getHost()
    {
        $return = "";
        if ($this->port === "443") {
            $return .= "tls://";
        } else {
            $return .= "tcp://";
        }
        $return .= $this->host;
        return $return;
    }

    # Erstellt den für die Get-Anfrage genutzten String
    private function generateGetString($query, $url, $language, $category)
    {
        $getString = "";

        # Skript:
        if (strlen($this->skript) > 0) {
            $getString .= $this->skript;
        } else {
            $getString .= "/";
        }

        # FormData:
        if (strlen($this->formData) > 0) {
            $getString .= "?" . $this->formData;
        }

        # Wir müssen noch einige Platzhalter in dem GET-String ersetzen:
        # Useragent
        if (strpos($getString, "<<USERAGENT>>")) {
            $getString = str_replace("<<USERAGENT>>", $this->urlEncode($this->useragent), $getString);
        }

        # Query
        if (strpos($getString, "<<QUERY>>")) {
            $getString = str_replace("<<QUERY>>", $this->urlEncode($query), $getString);
        }

        # IP
        if (strpos($getString, "<<IP>>")) {
            $getString = str_replace("<<IP>>", $this->urlEncode($this->ip), $getString);
        }

        # Language
        if (strpos($getString, "<<LANGUAGE>>")) {
            $getString = str_replace("<<LANGUAGE>>", $this->urlEncode($language), $getString);
        }

        # Category
        if (strpos($getString, "<<CATEGORY>>")) {
            $getString = str_replace("<<CATEGORY>>", $this->urlEncode($category), $getString);
        }

        # Affildata
        if (strpos($getString, "<<AFFILDATA>>")) {
            $getString = str_replace("<<AFFILDATA>>", $this->getOvertureAffilData($url), $getString);
        }
        return $getString;
    }

    # Wandelt einen String nach aktuell gesetztem inputEncoding dieser Searchengine in URL-Format um
    protected function urlEncode($string)
    {
        if (isset($this->inputEncoding)) {
            return urlencode(mb_convert_encoding($string, $this->inputEncoding));
        } else {
            return urlencode($string);
        }
    }

    # Liefert Sonderdaten für Yahoo
    private function getOvertureAffilData($url)
    {
        $affil_data = 'ip=' . $this->ip;
        $affil_data .= '&ua=' . $this->useragent;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $affil_data .= '&xfip=' . $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        $affilDataValue = $this->urlEncode($affil_data);
        # Wir benötigen die ServeUrl:
        $serveUrl = $this->urlEncode($url);

        return "&affilData=" . $affilDataValue . "&serveUrl=" . $serveUrl;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    # Artefaktmethoden

    public function getCurlInfo()
    {
        return curl_getinfo($this->ch);
    }

    public function getCurlErrors()
    {
        return curl_errno($this->ch);
    }

    public function addCurlHandle($mh)
    {
        curl_multi_add_handle($mh, $this->ch);
    }

    public function removeCurlHandle($mh)
    {
        curl_multi_remove_handle($mh, $this->ch);
    }
}
