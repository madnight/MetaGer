<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;
use Log;

class Yandex extends Searchengine
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

            # let's check if the query got unquoted
            # in that case we will ignore all results because that would mean
            # a string search (query between "") was wished and no results for that foudn
            $reask = $content->xpath("//yandexsearch/response/reask");
            if(sizeof($reask) !== 0 && $reask[0]->{"rule"}->__toString()){
                return;
            }


            $results = $content->xpath("//yandexsearch/response/results/grouping/group");
            foreach ($results as $result) {
                $title       = strip_tags($result->{"doc"}->{"title"}->asXML());
                $link        = $result->{"doc"}->{"url"}->__toString();
                $anzeigeLink = $link;
                $descr       = strip_tags($result->{"doc"}->{"headline"}->asXML());
                if (!$descr) {
                    $descr = strip_tags($result->{"doc"}->{"passages"}->asXML());
                }
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
            Log::error("A problem occurred parsing results from $this->name:\n" . $e->getMessage() . "\n" . $result);
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
            $resultCount = $content->xpath('//yandexsearch/response/results/grouping/found[@priority="all"]');
            if(!$resultCount || sizeof($resultCount) <= 0){
                return;
            }
            $resultCount = intval($resultCount[0]->__toString());
            $pageLast    = $content->xpath('//yandexsearch/response/results/grouping/page')[0];
            $pageLast    = intval($pageLast["last"]->__toString());
            if (count($this->results) <= 0 || $pageLast >= $resultCount) {
                return;
            }
            $next = new Yandex(simplexml_load_string($this->engine), $metager);
            $next->getString .= "&page=" . ($metager->getPage() + 1);
            $next->hash = md5($next->host . $next->getString . $next->port . $next->name);
        } catch (\Exception $e) {
            Log::error("A problem occurred parsing results from $this->name:\n" . $e->getMessage() . "\n" . $result);
            return;
        }
    }
}
