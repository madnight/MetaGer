<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Europeana extends Searchengine
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
            $content = json_decode($result);
        } catch (\Exception $e) {
            Log::error("Results from $this->name are not a valid json string");
            return;
        }
        if (!$content) {
            return;
        }
        $results = $content->items;
        foreach ($results as $result) {
            if (isset($result->edmPreview)) {
                $title = $result->title[0];
                if (preg_match("/(.+)\?.*/si", $result->guid, $match)) {
                    $link = $match[1];
                } else {
                    $link = "";
                }
                $anzeigeLink = $link;
                $descr       = "";
                $image       = urldecode($result->edmPreview[0]);
                $this->counter++;
                $this->results[] = new \App\Models\Result(
                    $this->engine,
                    $title,
                    $link,
                    $anzeigeLink,
                    $descr,
                    $this->gefVon,
                    $this->counter,
                    false,
                    $image
                );
            }
        }
    }

    public function getNext(\App\MetaGer $metager, $result)
    {
        $start = ($metager->getPage()) * 10 + 1;
        try {
            $content = json_decode($result);
        } catch (\Exception $e) {
            Log::error("Results from $this->name are not a valid json string");
            return;
        }
        if (!$content) {
            return;
        }
        if ($start > $content->totalResults) {
            return;
        }
        $next = new Europeana(simplexml_load_string($this->engine), $metager);
        $next->getString .= "&start=" . $start;
        $next->hash = md5($next->host . $next->getString . $next->port . $next->name);
        $this->next = $next;
    }
}
