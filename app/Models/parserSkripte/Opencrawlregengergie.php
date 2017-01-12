<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class Opencrawlregengergie extends Searchengine
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

            $results = $content->xpath('//rss/channel/item');
            $count   = 0;
            foreach ($results as $result) {
                if ($count > 10) {
                    break;
                }

                $title       = $result->{"title"}->__toString();
                $link        = $result->{"link"}->__toString();
                $anzeigeLink = $link;
                $descr       = strip_tags($result->{"description"}->__toString());
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
                $count++;
            }
        } catch (\Exception $e) {
            Log::error("A problem occurred parsing results from $this->name:");
            Log::error($e->getMessage());
            return;
        }
    }
}
