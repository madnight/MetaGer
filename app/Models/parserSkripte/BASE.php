<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class BASE extends Searchengine
{
    public $results = [];

    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults($result)
    {
        return;
    }
}
