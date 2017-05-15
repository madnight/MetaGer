<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;
use Log;

class Exalead extends Searchengine
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
            $results   = $content;
            $prefix    = "";
            $namespace = "";
            foreach ($results->getDocNamespaces() as $strPrefix => $strNamespace) {
                if (strlen($strPrefix) == 0) {
                    $prefix = "a"; //Assign an arbitrary namespace prefix.
                } else {
                    $prefix = "a";
                }
                $namespace = $strNamespace;
            }
            $results->registerXPathNamespace($prefix, $namespace);
            try {
                $results = $results->xpath("//a:hits/a:Hit");
            } catch (\ErrorException $e) {
                return;
            }
            foreach ($results as $result) {
                try {
                    $result->registerXPathNamespace($prefix, $namespace);
                    $title       = $result->xpath("a:metas/a:Meta[@name='title']/a:MetaString[@name='value']")[0]->__toString();
                    $link        = $result["url"]->__toString();
                    $anzeigeLink = $link;
                    $descr       = "";
                    if (sizeOf($result->xpath("a:metas/a:Meta[@name='metadesc']/a:MetaString[@name='value']")) === 0 && sizeOf($result->xpath("a:metas/a:Meta[@name='summary']/a:MetaText[@name='value']")) !== 0) {
                        $tmp = $result->xpath("a:metas/a:Meta[@name='summary']/a:MetaText[@name='value']");
                        foreach ($tmp as $el) {
                            $descr .= strip_tags($el->asXML());
                        }
                    } else {
                        $descr = $result->xpath("a:metas/a:Meta[@name='metadesc']/a:MetaString[@name='value']")[0]->__toString();
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
                } catch (\ErrorException $e) {
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::error("A problem occurred parsing results from $this->name:");
            Log::error($e->getMessage());
            return;
        }
    }
}
