<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Pixabay extends Searchengine
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

        $results = $content->hits;
        foreach ($results as $result) {
            $title       = $result->tags;
            $link        = $result->pageURL;
            $anzeigeLink = $link;
            $descr       = "";
            $image       = $result->previewURL;
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

    public function getNext(\App\MetaGer $metager, $result)
    {
        $page = $metager->getPage() + 1;
        try {
            $content = json_decode($result);
        } catch (\Exception $e) {
            Log::error("Results from $this->name are not a valid json string");
            return;
        }
        if (!$content) {
            return;
        }
        if ($page * 20 > $content->total) {
            return;
        }
        $next = new Pixabay(simplexml_load_string($this->engine), $metager);
        $next->getString .= "&page=" . $page;
        $next->hash = md5($next->host . $next->getString . $next->port . $next->name);
        $this->next = $next;
    }
}
