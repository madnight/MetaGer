<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;
use Log;

class Yacy extends Searchengine
{
    public $results = [];

    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults($result)
    {
        
        try {
            $content = json_decode($result, true);
            $content = $content["channels"];

            foreach($content as $channel){
                $items = $channel["items"];
                foreach($items as $item){
                    $title       = $item["title"];
                    $link        = $item["link"];
                    $anzeigeLink = $link;
                    $descr       = $item["description"];

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
                
            }
        } catch (\Exception $e) {
            Log::error("A problem occurred parsing results from $this->name:");
            Log::error($e->getMessage());
            return;
        }
    }
}
