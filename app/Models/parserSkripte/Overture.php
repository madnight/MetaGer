<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;
use Log;

class Overture extends Searchengine
{
    public $results = [];

    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults($result)
    {
        $result = preg_replace("/\r\n/si", "", $result);
        try {
            $content = simplexml_load_string($result);
            if (!$content) {
                return;
            }

            $results = $content->xpath('//Results/ResultSet[@id="inktomi"]/Listing');
            foreach ($results as $result) {
                $title       = $result["title"];
                $link        = $result->{"ClickUrl"}->__toString();
                $anzeigeLink = $result["siteHost"];
                $descr       = $result["description"];
                $this->counter++;
                $this->results[] = new \App\Models\Result(
                    $this->engine,
                    $title,
                    $link,
                    $anzeigeLink,
                    $descr,
                    $this->gefVon,
                    $this->counter
                );
            }

            # Nun noch die Werbeergebnisse:
            $ads = $content->xpath('//Results/ResultSet[@id="searchResults"]/Listing');
            foreach ($ads as $ad) {
                $title       = $ad["title"];
                $link        = $ad->{"ClickUrl"}->__toString();
                $anzeigeLink = $ad["siteHost"];
                $descr       = $ad["description"];
                $this->counter++;
                $this->ads[] = new \App\Models\Result(
                    $this->engine,
                    $title,
                    $link,
                    $anzeigeLink,
                    $descr,
                    $this->gefVon,
                    $this->counter
                );
            }
        } catch (\Exception $e) {
            Log::error("A problem occurred parsing results from $this->name:");
            Log::error($e->getMessage());
            return;
        }
    }

    public function getNext(\App\MetaGer $metager, $result)
    {
        $result = preg_replace("/\r\n/si", "", $result);
        try {
            $content = simplexml_load_string($result);
            if (!$content) {
                return;
            }
        } catch (\Exception $e) {
            Log::error("A problem occurred parsing results from $this->name:");
            Log::error($e->getMessage());
            return;
        }

        # Auslesen der Argumente für die nächste Suchseite:
        $result = preg_replace("/\r\n/si", "", $result);
        try {
            $content = simplexml_load_string($result);
        } catch (\Exception $e) {
            Log::error("Results from $this->name are not a valid json string");
            return;
        }
        if (!$content) {
            return;
        }
        $nextArgs = $content->xpath('//Results/NextArgs');
        if (isset($nextArgs[0])) {
            $nextArgs = $nextArgs[0]->__toString();
        } else {
            $nextArgs = $content->xpath('//Results/ResultSet[@id="inktomi"]/NextArgs');
            if (isset($nextArgs[0])) {
                $nextArgs = $nextArgs[0]->__toString();
            } else {
                return;
            }
        }

        # Erstellen des neuen Suchmaschinenobjekts und anpassen des GetStrings:
        $next            = new Overture(simplexml_load_string($this->engine), $metager);
        $next->getString = preg_replace("/&Keywords=.*?&/si", "&", $next->getString) . "&" . $nextArgs;
        $next->hash      = md5($next->host . $next->getString . $next->port . $next->name);
        $this->next      = $next;
    }
}
