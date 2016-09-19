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
    public $descr; # Die Beschreibung des Suchergebnisses
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
    public function __construct($provider, $titel, $link, $anzeigeLink, $descr, $gefVon, $sourceRank, $partnershop = false, $image = "", $price = 0)
    {
        $provider          = simplexml_load_string($provider);
        $this->titel       = strip_tags(trim($titel));
        $this->link        = trim($link);
        $this->anzeigeLink = trim($anzeigeLink);
        $this->descr       = strip_tags(trim($descr), '<p>');
        $this->descr       = preg_replace("/\n+/si", " ", $this->descr);
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
        $this->valid          = true;
        $this->host           = @parse_url($link, PHP_URL_HOST);
        $this->strippedHost   = $this->getStrippedHost($this->anzeigeLink);
        $this->strippedDomain = $this->getStrippedDomain($this->strippedHost);
        $this->strippedLink   = $this->getStrippedLink($this->anzeigeLink);
        $this->rank           = 0;
        $this->partnershop    = $partnershop;
        $this->image          = $image;
        $this->price          = $price;
    }

    /* Ranked das Ergebnis nach folgenden Aspekten:
     *  Startwert 0
     *  + 0.02 * Sourcerank (20 - Position in Ergebnisliste des Suchanbieters)
     *  * Engine-Boost
     */
    public function rank(\App\MetaGer $metager)
    {
        $rank = 0;

        $eingabe = $metager->getQ();

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

        $this->rank = $rank;
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
        if (strlen($this->descr) > 80 && strlen($link) > 0) {
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
            $text = $this->titel . " " . $this->descr;
            $path = app_path() . "/Models/lang.pl";
            $lang = exec("echo '$text' | $path");

            if ($metager->getLang() !== $lang) {
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

        # Phrasensuche:
        $text = strtolower($this->titel) . " " . strtolower($this->descr);
        foreach ($metager->getPhrases() as $phrase) {
            if (strpos($text, $phrase) === false) {
                return false;
            }

        }

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

        /* Der Dublettefilter, der sicher stellt,
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
    private function getStrippedHost($link)
    {
        if (strpos($link, "http") !== 0) {
            $link = "http://" . $link;
        }

        $link = @parse_url($link, PHP_URL_HOST);
        $link = preg_replace("/^www\./si", "", $link);
        return $link;
    }

    /* Entfernt "http://", "www" und Parameter von einem Link
     *  Dieser wird dabei in die Form:
     *  "http://www.foo.bar.de/test?ja=1" -> "foo.bar.de/test"
     *  gebracht.
     */
    private function getStrippedLink($link)
    {
        if (strpos($link, "http") !== 0) {
            $link = "http://" . $link;
        }

        $host = $this->strippedHost;
        $path = @parse_url($link, PHP_URL_PATH);
        return $host . $path;
    }

    /* Liest aus einem Link die Domain.
     *  Dieser wird dabei in die Form:
     *  "http://www.foo.bar.de/test?ja=1" -> "bar.de"
     *  gebracht.
     */
    private function getStrippedDomain($link)
    {
        if (preg_match("/([^\.]*\.[^\.]*)$/si", $link, $match)) {
            return $match[1];
        } else {
            return $link;
        }
    }

    # Erstellt aus einem Link einen Proxy-Link für unseren Proxy-Service
    private function generateProxyLink($link)
    {
        if (!$link) {
            return "";
        }

        $tmp = $link;
        $tmp = preg_replace("/\r?\n$/s", "", $tmp);
        $tmp = preg_replace("#^([\w+.-]+)://#s", "$1/", $tmp);
        return "https://proxy.suma-ev.de/cgi-bin/nph-proxy.cgi/en/I0/" . $tmp;

    }

    # Getter

    public function getRank()
    {
        return $this->rank;
    }
}
