<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;
use Log;

class BASE extends Searchengine
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

            $results = $content->xpath('//response/result/doc');
            foreach ($results as $result) {
                // searches for the fitting values of name as in
                // <str name = "dctitle">Digitisation of library collections</str>
                foreach ($result as $attribute) {
                    switch ((string) $attribute['name']) {
                        case 'dctitle':
                            $title = $attribute;
                            break;
                        case 'dclink':
                            $link        = $attribute;
                            $anzeigeLink = $link;
                            break;
                        case 'dcdescription':
                            $descr = $attribute;
                            break;
                    }
                }
                if (isset($title) && isset($link) && isset($anzeigeLink) && isset($descr)) {
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
