<?php

namespace App\Models;

/* Die Klasse Result sammelt alle Informationen über ein einzelnes Suchergebnis.
 *  Die Results werden von den Suchmaschinenspezifischen Parser-Skripten erstellt.
 */
class Result
{
    public $provider; # Die Engine von der das Suchergebnis kommt
    public $titel; # Der Groß Angezeigte Name für das Suchergebnis
    public $link; # Der Link auf die Ergebnisseite
    public $anzeigeLink; # Der tatsächlich angezeigte Link (rein optisch)
    public $descr; # Die eventuell gekürzte Beschreibung des Suchergebnisses
    public $longDescr; # Die ungekürzte Beschreibung des Suchergebnisses
    public $gefVon; # Das bei Suchergebnissen angezeigte von ... mitsamt Verlinkung
    public $sourceRank; # Das Ranking für dieses Suchergebnis von der Seite, die es geliefert hat (implizit durch Ergebnisreihenfolge: 20 - Position in Ergebnisliste)
    public $partnershop; # Ist das Ergebnis von einem Partnershop? (bool)
    public $image; # Ein Vorschaubild für das Suchergebnis (als URL)

    public $proxyLink; # Der Link für die Seite über unseren Proxy-Service
    public $engineBoost = 1; # Der Boost für den Provider des Suchergebnisses
    public $valid       = true; # Ob das Ergebnis noch gültig ist (bool)
    public $host; # Der aus dem Link gelesene Host des Suchergebnisses
    public $strippedHost; # Der Host      in Form "foo.bar.de"
    public $strippedDomain; # Die Domain    in Form "bar.de"
    public $strippedLink; # Der Link      in Form "foo.bar.de/test"
    public $rank; # Das Ranking für das Ergebnis

    # Erstellt ein neues Ergebnis
    #public function __construct($provider, $titel, $link, $anzeigeLink, $descr, $gefVon, $sourceRank, $partnershop = false, $image = "", $price = 0, $additionalInformation = [])
    public function __construct($provider, $titel, $link, $anzeigeLink, $descr, $gefVon, $sourceRank, $additionalInformation = [])
    {
        $provider          = simplexml_load_string($provider);
        $this->titel       = strip_tags(trim($titel));
        $this->link        = trim($link);
        $this->anzeigeLink = trim($anzeigeLink);
        $this->descr       = strip_tags(trim($descr), '<p>');
        $this->descr       = preg_replace("/\n+/si", " ", $this->descr);
        $this->longDescr   = $this->descr;
        if (strlen($this->descr) > 250) {
            $this->descr = wordwrap($this->descr, 250);
            $this->descr = substr($this->descr, 0, strpos($this->descr, "\n"));

        }
        $this->gefVon     = trim($gefVon);
        $this->proxyLink  = $this->generateProxyLink($this->link);
        $this->sourceRank = $sourceRank;
        if ($this->sourceRank <= 0 || $this->sourceRank > 20) {
            $this->sourceRank = 20;
        }
        $this->sourceRank = 20 - $this->sourceRank;
        if (isset($provider["engineBoost"])) {
            $this->engineBoost = floatval($provider["engineBoost"]->__toString());
        } else {
            $this->engineBoost = 1;
        }
        $this->valid                 = true;
        $this->host                  = @parse_url($link, PHP_URL_HOST);
        $this->strippedHost          = $this->getStrippedHost($this->anzeigeLink);
        $this->strippedDomain        = $this->getStrippedDomain($this->strippedHost);
        $this->strippedLink          = $this->getStrippedLink($this->anzeigeLink);
        $this->rank                  = 0;
        $this->partnershop           = isset($additionalInformation["partnershop"]) ? $additionalInformation["partnershop"] : false;
        $this->image                 = isset($additionalInformation["image"]) ? $additionalInformation["image"] : "";
        $this->price                 = isset($additionalInformation["price"]) ? $additionalInformation["price"] : 0;
        $this->additionalInformation = $additionalInformation;
    }

    /* Ranked das Ergebnis nach folgenden Aspekten:
     *  Startwert 0
     *  + 0.02 * Sourcerank (20 - Position in Ergebnisliste des Suchanbieters)
     *  * Engine-Boost
     */
    public function rank($eingabe, $phrases = [])
    { 

        $rank = 0;

        # Boost für Source Ranking
        $rank += ($this->sourceRank * 0.02);

        # Boost für passende ??? URL
        $rank += $this->calcURLBoost($eingabe);

        # Boost für Vorkommen der Suchwörter:
        $rank += $this->calcSuchwortBoost($eingabe);

        # Boost für Suchmaschine
        if ($this->engineBoost > 0) {
            $rank *= floatval($this->engineBoost);
        }

        # Runter Ranken von Yandex Ergebnissen mit zu viel kyrillischen Texten
        if (stripos($this->gefVon, "yandex") !== false) {
            $rank -= $this->calcYandexBoost($eingabe);
        }

        if(!empty($phrases)) {
            $rank += $this->calcPhraseSearchBoost($phrases);
        }

        $this->rank = $rank;
    }

    # Berechnet, ob dieses Suchergebnis einen Malus erhalten soll, oder nicht
    # Übergeben werden alle Yandex Ergebnisse
    # Wenn die Suchworte kein kyrillisches Zeichen enthalten, wird das Ergebnis schlechter bewertet,
    # falls es selbst zu viele kyrillische Zeichen enthält
    private function calcYandexBoost($tmpEingabe)
    {
        $maxRatio = 0.1; # Gibt den Prozentsatz von Kyrillischen Zeichen in Titel und Beschreibung an, ab dem das Ergebnis runter gerankt werden soll
        if (preg_match('/[А-Яа-яЁё]/u', $tmpEingabe) === 1) {
            # Das Suchwort enthält kyrillische Zeichen, also dürfen es auch die Ergebnisse
            return 0;
        } else {
            # Wir überprüfen das Verhältnis von Kyrillischen Zeichen im Titel
            if (preg_match_all('/[А-Яа-яЁё]/u', $this->titel, $matches)) {
                $count     = sizeof($matches[0]);
                $titleSize = strlen($this->titel);
                $percKyr   = $count / $titleSize;
                if ($percKyr > $maxRatio) {
                    return 5;
                }
            }
            # Wir überprüfen das Verhältnis von Kyrillischen Zeichen in der Beschreibung
            if (preg_match_all('/[А-Яа-яЁё]/u', $this->descr, $matches)) {
                $count     = sizeof($matches[0]);
                $descrSize = strlen($this->descr);
                $percKyr   = $count / $descrSize;
                if ($percKyr > $maxRatio) {
                    return 5;
                }
            }
        }
        return 0;
    }

    private function calcPhraseSearchBoost($phrases) {

        $containsPhrase = true;
        foreach($phrases as $phrase) {
            if(strstr($this->longDescr, $phrase) == false) {
                $containsPhrase = false;
            }
        }
        return $containsPhrase ? 1 : 0;
    }

    # Berechnet den Ranking-Boost durch ??? URL
    public function calcURLBoost($tmpEingabe)
    {
        $link = $this->anzeigeLink;
        if (strpos($link, "http") !== 0) {
            $link = "http://" . $link;
        }
        $link    = @parse_url($link, PHP_URL_HOST) . @parse_url($link, PHP_URL_PATH);
        $tmpLi   = $link;
        $count   = 0;
        $tmpLink = "";
        # Löscht verschiedene unerwünschte Teile aus $link und $tmpEingabe
        $regex = [
            "/\s+/si", # Leerzeichen
            "/http:/si", # "http:"
            "/https:/si", # "https:"
            "/www\./si", # "www."
            "/\//si", # "/"
            "/\./si", # "."
            "/-/si", # "-"
        ];
        foreach ($regex as $reg) {
            $link       = preg_replace($regex, "", $link);
            $tmpEingabe = preg_replace($regex, "", $tmpEingabe);
        }
        foreach (str_split($tmpEingabe) as $char) {
            if (!$char
                || !$tmpEingabe
                || strlen($tmpEingabe) === 0
                || strlen($char) === 0
            ) {
                continue;
            }
            if (strpos(strtolower($tmpLink), strtolower($char)) >= 0) {
                $count++;
                $tmpLink = str_replace(urlencode($char), "", $tmpLink);
            }
        }
        if (strlen($this->descr) > 40 && strlen($link) > 0) {
            return $count / ((strlen($link)) * 60); # ???
        } else {
            return 0;
        }
    }

    # Berechnet den Ranking-Boost durch das Vorkommen von Suchwörtern
    private function calcSuchwortBoost($tmpEingabe)
    {
        $maxRank        = 0.1;
        $tmpTitle       = $this->titel;
        $tmpDescription = $this->descr;
        $isWithin       = false;
        $tmpRank        = 0;
        $tmpEingabe     = preg_replace("/\b\w{1,3}\b/si", "", $tmpEingabe);
        $tmpEingabe     = preg_replace("/\s+/si", " ", $tmpEingabe);

        foreach (explode(" ", trim($tmpEingabe)) as $el) {
            if (strlen($tmpTitle) === 0 || strlen($el) === 0 || strlen($tmpDescription) === 0) {
                continue;
            }

            $el = preg_quote($el, "/");
            if (strlen($tmpTitle) > 0) {
                if (preg_match("/\b$el\b/si", $tmpTitle)) {
                    $tmpRank += .7 * .6 * $maxRank;
                } elseif (strpos($tmpTitle, $el) !== false) {
                    $tmpRank += .3 * .6 * $maxRank;
                }
            }
            if (strlen($tmpDescription) > 0) {
                if (preg_match("/\b$el\b/si", $tmpDescription)) {
                    $tmpRank += .7 * .4 * $maxRank;
                } elseif (strpos($tmpDescription, $el) !== false) {
                    $tmpRank += .3 * .4 * $maxRank;
                }
            }
        }

        $tmpRank /= sizeof(explode(" ", trim($tmpEingabe))) * 10;
        return $tmpRank;
    }

    # Überprüft ob das Ergebnis aus irgendwelchen Gründen unerwünscht ist.
    public function isValid(\App\MetaGer $metager)
    {
        # Perönliche URL und Domain Blacklist
        if (in_array($this->strippedHost, $metager->getUserHostBlacklist())
            || in_array($this->strippedDomain, $metager->getUserDomainBlacklist())) {
            return false;
        }

        # Allgemeine URL und Domain Blacklist
        if ($this->strippedHost !== "" && (in_array($this->strippedHost, $metager->getDomainBlacklist()) || in_array($this->strippedLink, $metager->getUrlBlacklist()))) {
            return false;
        }

        # Eventueller Sprachfilter
        if ($metager->getLang() !== "all") {
            if (!isset($this->langCode) || $this->langCode === null || $metager->getLang() !== $this->langCode) {
                return false;
            }

        }

        # Stopworte
        foreach ($metager->getStopWords() as $stopWord) {
            $text = $this->titel . " " . $this->descr;
            if (stripos($text, $stopWord) !== false) {
                return false;
            }
        }

        /*
        # Phrasensuche:
        $text = strtolower($this->titel) . " " . strtolower($this->descr);
        foreach ($metager->getPhrases() as $phrase) {
        if (strpos($text, $phrase) === false) {
        return false;
        }
        }
         */
        /* Der Host-Filter der sicherstellt,
         *  dass von jedem Host maximal 3 Links angezeigt werden.
         *  Diese Überprüfung führen wir unter bestimmten Bedingungen nicht durch.
         */
        if ($metager->getSite() === "" &&
            strpos($this->strippedHost, "ncbi.nlm.nih.gov") === false &&
            strpos($this->strippedHost, "twitter.com") === false &&
            strpos($this->strippedHost, "www.ladenpreis.net") === false &&
            strpos($this->strippedHost, "ncbi.nlm.nih.gov") === false &&
            strpos($this->strippedHost, "www.onenewspage.com") === false) {
            $count = $metager->getHostCount($this->strippedHost);
            if ($count >= 3) {
                return false;
            }
        }

        /* Der Dublettenfilter, der sicher stellt,
         *  dass wir nach Möglichkeit keinen Link doppelt in der Ergebnisliste haben.
         */
        if ($metager->addLink($this->strippedLink)) {
            $metager->addHostCount($this->strippedHost);
            return true;
        } else {
            return false;
        }
    }

    /* Liest aus einem Link den Host.
     *  Dieser wird dabei in die Form:
     *  "http://www.foo.bar.de/test?ja=1" -> "foo.bar.de"
     *  gebracht.
     */
    public function getStrippedHost($link)
    {
        $match = $this->getUrlElements($link);
        return $match['host'];
    }

    /* Entfernt "http://", "www" und Parameter von einem Link
     *  Dieser wird dabei in die Form:
     *  "http://www.foo.bar.de/test?ja=1" -> "foo.bar.de/test"
     *  gebracht.
     */
    public function getStrippedLink($link)
    {
        $match = $this->getUrlElements($link);
        return $match['host'] . $match['path'];
    }

    /* Liest aus einem Link die Domain.
     *  Dieser wird dabei in die Form:
     *  "http://www.foo.bar.de/test?ja=1" -> "bar.de"
     *  gebracht.
     */
    public function getStrippedDomain($link)
    {
        $match = $this->getUrlElements($link);
        return $match['domain'];
    }

    # Erstellt aus einem Link einen Proxy-Link für unseren Proxy-Service
    public function generateProxyLink($link)
    {
        if (!$link) {
            return "";
        }

        # Link to our new Proxy software:
        $pw = md5(env('PROXY_PASSWORD') . $link);

        $proxyUrl = base64_encode(str_rot13($link));
        $proxyUrl = urlencode(str_replace("/", "<<SLASH>>", $proxyUrl));

        return "https://proxy.suma-ev.de/" . $pw . "/" . $proxyUrl;
/*
$tmp = $link;
$tmp = preg_replace("/\r?\n$/s", "", $tmp);
$tmp = str_replace("=", "=3d", $tmp);
$tmp = str_replace("?", "=3f", $tmp);
$tmp = str_replace("%", "=25", $tmp);
$tmp = str_replace("&", "=26", $tmp);
$tmp = str_replace(";", "=3b", $tmp);
$tmp = preg_replace("#^([\w+.-]+)://#s", "$1/", $tmp);
$tmp = str_replace("//", "/=2f", $tmp);
return "https://proxy.suma-ev.de/mger/nph-proxy.cgi/en/w0/" . $tmp;
 */
    }

    /* Liest aus einer URL alle Informationen aus
     * https://max:muster@www.example.site.page.com:8080/index/indexer/list.html?p1=A&p2=B#ressource
     * (?:((?:http)|(?:https))(?::\/\/))?
     * https://                  => [1] = http / https
     * (?:([a-z0-9.\-_~]+):([a-z0-9.\-_~]+)@)?
     * username:password@        => [2] = username, [3] = password
     * (?:(www)(?:\.))?
     * www.                      => [4] = www
     * ((?:(?:[a-z0-9.\-_~]+\.)+)?([a-z0-9.\-_~]+\.[a-z0-9.\-_~]+))
     * example.site.page.com     => [5] = example.site.page.com, [6] = page.com
     * (?:(?::)(\d+))?
     * :8080                     => [7] = 8080
     * ((?:(?:\/[a-z0-9.\-_~]+)+)(?:\.[a-z0-9.\-_~]+)?)?
     * /index/indexer/list.html  => [8] = /index/indexer/list.html
     * (\?[a-z0-9.\-_~]+=[a-z0-9.\-_~]+(?:&[a-z0-9.\-_~]+=[a-z0-9.\-_~]+)*)?
     * ?p1=A&p2=B                => [9] = ?p1=A&p2=B
     * (?:(?:#)([a-z0-9.\-_~]+))?
     * #ressource                => [10] = ressource
     */
    public function getUrlElements($url)
    {
        if (!preg_match("/(?:((?:http)|(?:https))(?::\/\/))?(?:([a-z0-9.\-_~]+):([a-z0-9.\-_~]+)@)?(?:(www)(?:\.))?((?:(?:[a-z0-9.\-_~]+\.)+)?([a-z0-9.\-_~]+\.[a-z0-9.\-_~]+))(?:(?::)(\d+))?((?:(?:\/[a-z0-9.\-_~]+)+)(?:\.[a-z0-9.\-_~]+)?)?(\?[a-z0-9.\-_~]+=[a-z0-9.\-_~]+(?:&[a-z0-9.\-_~]+=[a-z0-9.\-_~]+)*)?(?:(?:#)([a-z0-9.\-_~]+))?/i", $url, $match)) {
            return;
        } else {
            $re = [];
            if (isset($match[1])) {$re['schema'] = $match[1];} else { $re['schema'] = "";};
            if (isset($match[2])) {$re['username'] = $match[2];} else { $re['username'] = "";};
            if (isset($match[3])) {$re['password'] = $match[3];} else { $re['password'] = "";};
            if (isset($match[4])) {$re['web'] = $match[4];} else { $re['web'] = "";};
            if (isset($match[5])) {$re['host'] = $match[5];} else { $re['host'] = "";};
            if (isset($match[6])) {$re['domain'] = $match[6];} else { $re['domain'] = "";};
            if (isset($match[7])) {$re['port'] = $match[7];} else { $re['port'] = "";};
            if (isset($match[8])) {$re['path'] = $match[8];} else { $re['path'] = "";};
            if (isset($match[9])) {$re['query'] = $match[9];} else { $re['query'] = "";};
            if (isset($match[10])) {$re['fragment'] = $match[10];} else { $re['fragment'] = "";};
            return $re;
        }
    }

    # Getter

    public function getRank()
    {
        return $this->rank;
    }

    public function getDate()
    {
        if (isset($this->additionalInformation["date"])) {
            return $this->additionalInformation["date"];
        } else {
            return null;
        }
    }

    public function getLangString()
    {
        $string = "";

        $string .= $this->titel;
        $string .= $this->descr;

        return $string;
    }
}
