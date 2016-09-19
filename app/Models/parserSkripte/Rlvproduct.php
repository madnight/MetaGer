<?php

namespace app\Models\parserSkripte;

use App\Models\Searchengine;

class RlvProduct extends Searchengine
{
    public $results = [];

    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults($result)
    {
        # try
        # {
        $results = json_decode($result, true);
        $counter = 0;
        foreach ($results["products"] as $result) {
            $counter++;
            $image            = $result["productImage"];
            $image            = str_replace("//", "https://", $image);
            $this->products[] = new \App\Models\Result(
                $this->engine,
                $result["productTitle"],
                $result["shopLink"],
                $result["shopLink"],
                "",
                $result["shopTitle"],
                $counter,
                $partnershop = false,
                $image,
                $result["price"]
            );
        }
    }
}
