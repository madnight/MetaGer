<?php

namespace App\Models\parserSkripte;

use App\Models\Searchengine;

class Minisucher extends Searchengine
{

    public function __construct(\SimpleXMLElement $engine, \App\MetaGer $metager)
    {
        parent::__construct($engine, $metager);
        # Für die Newssuche stellen wir die Minisucher auf eine Sortierung nach Datum um.
        if($metager->getFokus() === "nachrichten"){
            $this->getString .= "sort=" . $this->urlencode("documentDate desc");
        }

    }

    public function loadResults($content)
    {
        try {
            $content = simplexml_load_string($content);
        } catch (\Exception $e) {
            return;
        }
        if (!$content) {
            return;
        }
        $results = $content->xpath('//response/result/doc');

        $string = "";

        $counter         = 0;
        $providerCounter = [];
        foreach ($results as $result) {
            try {
                $counter++;
                $result = simplexml_load_string($result->saveXML());

                $title        = $result->xpath('//doc/arr[@name="title"]/str')[0]->__toString();
                $link         = $result->xpath('//doc/str[@name="url"]')[0]->__toString();
                $anzeigeLink  = $link;
                $descr        = "";
                $descriptions = $content->xpath("//response/lst[@name='highlighting']/lst[@name='$link']/arr[@name='content']/str");
                foreach ($descriptions as $description) {
                    $descr .= $description->__toString();
                }
                $descr    = strip_tags($descr);

                $dateString = $result->xpath('//doc/date[@name="documentDate"]')[0]->__toString();

                $date = date_create_from_format("Y-m-d\TH:i:s\Z", $dateString);

                $dateVal = $date->getTimestamp();

                $additionalInformation = ['date' => $dateVal];

                $minism = implode(", ", $this->subcollections);
                $gefVon = "<a href=\"https://metager.de\" target=\"_blank\" rel=\"noopener\">Minisucher: $minism </a>";

                $this->results[] = new \App\Models\Result(
                    $this->engine,
                    $title,
                    $link,
                    $link,
                    $descr,
                    $gefVon,
                    $counter,
                    $additionalInformation
                );
            } catch (\ErrorException $e) {
                continue;
            }
        }

    }

}
