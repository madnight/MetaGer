<?php

namespace app\Models\parserSkripte;

use App\Models\Result;
use App\Models\Searchengine;

class Onenewspagegermany extends Searchengine
{
    public $results     = [];
    public $resultCount = 0;

    private $offset = 0;
    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults($result)
    {
        $counter = 0;
        foreach (explode("\n", $result) as $line) {
            $line = trim($line);
            if (strlen($line) > 0) {
                # Hier bekommen wir jedes einzelne Ergebnis
                $result = explode("|", $line);
                if (sizeof($result) < 3) {
                    continue;
                }
                $counter++;
                $this->results[] = new Result(
                    $this->engine,
                    trim(strip_tags($result[0])),
                    $result[2],
                    $result[2],
                    $result[1],
                    $this->gefVon,
                    $counter
                );
            }

        }
        if (count($this->results) > $this->resultCount) {
            $this->resultCount += count($this->results);
        }

    }

    public function getLast(\App\MetaGer $metager, $result)
    {
        if ($metager->getPage() <= 1) {
            return;
        }

        $last              = new Onenewspagegermany(simplexml_load_string($this->engine), $metager);
        $last->resultCount = $this->resultCount;
        $last->offset      = $this->offset - $this->resultCount;
        $last->getString .= "&o=" . $last->offset;
        $last->hash = md5($last->host . $last->getString . $last->port . $last->name);
        $this->last = $last;
    }

    public function getNext(\App\MetaGer $metager, $result)
    {
        if (count($this->results) <= 0) {
            return;
        }

        $next              = new Onenewspagegermany(simplexml_load_string($this->engine), $metager);
        $next->resultCount = $this->resultCount;
        $next->offset      = $this->offset + $this->resultCount;
        $next->getString .= "&o=" . $next->offset;
        $next->hash = md5($next->host . $next->getString . $next->port . $next->name);
        $this->next = $next;
    }
}
