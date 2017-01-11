<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Yacy extends Searchengine
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

            $results = $content->xpath("//rss/channel/item");
            if (!$results) {
                return;
            }

            foreach ($results as $res) {
                $title       = $res->{"title"};
                $link        = $res->{"link"};
                $anzeigeLink = $link;
                $descr       = $res->{"description"};

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
            Log::error("A problem occurred parsing results from $this->name");
            return;
        }
    }
}
