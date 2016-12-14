<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Openclipart extends Searchengine
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
        $results = $content->payload;
        foreach ($results as $result) {
            $title       = $result->title;
            $link        = $result->detail_link;
            $anzeigeLink = $link;
            $descr       = $result->description;
            $image       = $result->svg->png_thumb;
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
        try {
            $content = json_decode($result);
        } catch (\Exception $e) {
            Log::error("Results from $this->name are not a valid json string");
            return;
        }
        if (!$content) {
            return;
        }
        if ($content->info->current_page > $content->info->pages) {
            return;
        }
        $next = new Openclipart(simplexml_load_string($this->engine), $metager);
        $next->getString .= "&page=" . ($metager->getPage() + 1);
        $next->hash = md5($next->host . $next->getString . $next->port . $next->name);
        $this->next = $next;
    }
}
