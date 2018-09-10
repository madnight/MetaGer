<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;
use Log;

class Scopia extends Searchengine
{
    public $results = [];

    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults($result)
    {
        $result = html_entity_decode($result);
        $result = str_replace("&", "&amp;", $result);
        try {

            $content = simplexml_load_string($result);
            if (!$content) {
                return;
            }

            $results = $content->xpath('//results/result');
            foreach ($results as $result) {
                $title = $result->title->__toString();
                $link = $result->url->__toString();
                $anzeigeLink = $link;
                $descr = $result->description->__toString();
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
        } catch (\Exception $e) {
            Log::error("A problem occurred parsing results from $this->name:");
            Log::error($e->getMessage());
            return;
        }
    }

    public function getNext(\App\MetaGer $metager, $result)
    {
        $result = html_entity_decode($result);
        $result = str_replace("&", "&amp;", $result);
        try {
            $content = simplexml_load_string($result);

        } catch (\Exception $e) {
            Log::error("A problem occurred parsing results from $this->name:");
            Log::error($e->getMessage());
            return;
        }

        if (!$content) {
            return;
        }

        $more = $content->xpath('//results/more')[0]->__toString() === "1" ? true : false;

        if ($more) {
            $results = $content->xpath('//results/result');
            $number = $results[sizeof($results) - 1]->number->__toString();
            # Erstellen des neuen Suchmaschinenobjekts und anpassen des GetStrings:
            $next = new Scopia(simplexml_load_string($this->engine), $metager);
            $next->getString = preg_replace("/\\?s=.*?&/si", "?s=" . $number, $next->getString);
            $next->hash = md5($next->host . $next->getString . $next->port . $next->name);
            $this->next = $next;
        }

    }
}
