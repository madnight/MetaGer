<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Flickr extends Searchengine
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
        } catch (\Exception $e) {
            Log::error("Results from $this->name are not a valid json string");
            return;
        }
        if (!$content) {
            return;
        }
        $results = $content->xpath('//photos/photo');
        foreach ($results as $result) {
            $title       = $result["title"]->__toString();
            $link        = "https://www.flickr.com/photos/" . $result["owner"]->__toString() . "/" . $result["id"]->__toString();
            $anzeigeLink = $link;
            $descr       = "";
            $image       = "http://farm" . $result["farm"]->__toString() . ".staticflickr.com/" . $result["server"]->__toString() . "/" . $result["id"]->__toString() . "_" . $result["secret"]->__toString() . "_t.jpg";
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
        $page    = $metager->getPage() + 1;
        $result  = preg_replace("/\r\n/si", "", $result);
        $content = simplexml_load_string($result);
        $results = $content->xpath('//photos')[0];
        try {
            $content = simplexml_load_string($result);
        } catch (\Exception $e) {
            Log::error("Results from $this->name are not a valid json string");
            return;
        }
        if (!$content) {
            return;
        }
        if ($page >= intval($results["pages"]->__toString())) {
            return;
        }
        $next = new Flickr(simplexml_load_string($this->engine), $metager);
        $next->getString .= "&page=" . $page;
        $next->hash = md5($next->host . $next->getString . $next->port . $next->name);
        $this->next = $next;
    }
}
