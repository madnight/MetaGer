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
                $title                 = $result[0];
                $link                  = $result[2];
                $anzeigeLink           = $link;
                $descr                 = $result[1];
                $additionalInformation = sizeof($result) > 3 ? ['date' => intval($result[3])] : [];

                $counter++;
                $this->results[] = new Result(
                    $this->engine,
                    $title,
                    $link,
                    $anzeigeLink,
                    $descr,
                    $this->gefVon,
                    $this->counter,
                    $additionalInformation
                );
            }

        }
        if (count($this->results) > $this->resultCount) {
            $this->resultCount += count($this->results);
        }

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
