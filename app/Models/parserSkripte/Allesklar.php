<?php

namespace App\Models\parserSkripte;

use App\Models\Searchengine;
use Log;
use Symfony\Component\DomCrawler\Crawler;

class Allesklar extends Searchengine
{
    protected $tds = "";
    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
    }

    public function loadResults($result)
    {
        $crawler = new Crawler(utf8_decode($result));
        $crawler = $crawler
            ->filter('table[width=585]')
            ->reduce(function (Crawler $node, $i) {
                // The first 5 elements are additional information
                return $i >= 5;
            });
        $this->counter = 0;
        $crawler->each(function (Crawler $node, $i) {
            // Only the first 20 elements are actual search results
            if ($i < 20) {
                try {
                    $titleTag = $node->filter('tr > td > a.katalogtitel')->first();
                    $title    = trim($titleTag->text());
                    $link     = $titleTag->attr('href');
                    // Sometimes the description is in the 3rd element
                    $descr = trim($node->filter('tr > td.bodytext')->eq(2)->text());
                    if (strlen($descr) <= 2) {
                        $descr = trim($node->filter('tr > td.bodytext')->eq(3)->text());
                    }
                    $this->counter++;
                    $this->results[] = new \App\Models\Result(
                        $this->engine,
                        $title,
                        $link,
                        $link,
                        $descr,
                        $this->gefVon,
                        $this->counter
                    );
                } catch (\Exception $e) {
                    Log::error("A problem occurred parsing results from $this->name:");
                    Log::error($e->getMessage());
                    return;
                }
            }
        });
    }

}
