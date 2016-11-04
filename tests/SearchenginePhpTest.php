<?php

use App\MetaGer;
use App\models\parserSkripte\Bing;
use Illuminate\Http\Request;

class SearchenginePhpTest extends TestCase
{
    // Die Testfunktion die PHP Unit aufruft
    // Ruft alle anderen Untertests auf
    public function test()
    {
        $this->constructionTest();
        $this->enablingTest();
    }

    // Prüft ob aus einer XML korrekt das Suchmaschinen-Objekt erstellt wird
    public function constructionTest()
    {
        $engines = simplexml_load_file("tests/testSumas.xml")->xpath("suma");
        $metager = new MetaGer();
        $request = new Request(["focus" => "web"]);
        $metager->parseFormData($request);
        $searchengine = new Bing($engines[0], $metager);

        $this->assertEquals('<suma name="minism" host="www.suchmaschine.de" skript="/suche/" formData="sprache=de&amp;sortieren=true&amp;queue=&lt;&lt;QUERY&gt;&gt;&amp;rows=&lt;&lt;COUNT&gt;&gt;&amp;fq=subcollection:&lt;&lt;SUBCOLLECTIONS&gt;&gt;" package="suchmaschine" displayName="Meine Suchmaschine" homepage="www.suchmaschine.de/welcome" port="443" inputEncoding="Latin1" userSelectable="1" type="bilder" engineBoost="1.2" additionalHeaders="$#!#$" hasSiteSearch="1" cacheDuration="60"/>',
            $searchengine->engine);
        $this->assertEquals(true,
            $searchengine->enabled);
        $this->assertEquals('Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1',
            $searchengine->useragent);
        $this->assertEquals(null,
            $searchengine->ip);
        $this->assertEquals('<a href="www.suchmaschine.de/welcome" target="_blank">Meine Suchmaschine</a>',
            $searchengine->gefVon);
        #$this->assertEquals('0.68813000 1476867147', $searchengine->startTime);
        $this->assertEquals('/suche/?sprache=de&sortieren=true&queue=&rows=<<COUNT>>&fq=subcollection:<<SUBCOLLECTIONS>>',
            $searchengine->getString); # Enthält auch Testen von generateGetString(), urlEncode() und getOvertureAffilData() (nicht in der aktuellen Version)
        $this->assertEquals('b1ac991618a8ffc0dab6b9bbb913841e',
            $searchengine->hash);
        $this->assertEquals('86a9106ae65537651a8e456835b316ab',
            $searchengine->resultHash);
        $this->assertEquals(true,
            $searchengine->canCache);
        $this->assertEquals('minism',
            $searchengine->name);
        $this->assertEquals('www.suchmaschine.de',
            $searchengine->host);
        $this->assertEquals('/suche/',
            $searchengine->skript);
        $this->assertEquals('sprache=de&sortieren=true&queue=<<QUERY>>&rows=<<COUNT>>&fq=subcollection:<<SUBCOLLECTIONS>>',
            $searchengine->formData);
        $this->assertEquals('suchmaschine',
            $searchengine->package);
        $this->assertEquals('Meine Suchmaschine',
            $searchengine->displayName);
        $this->assertEquals('443',
            $searchengine->port);
        $this->assertEquals('Latin1',
            $searchengine->inputEncoding);
        $this->assertEquals('1',
            $searchengine->userSelectable);
        $this->assertEquals('bilder',
            $searchengine->type);
        $this->assertEquals('1.2',
            $searchengine->engineBoost);
        $this->assertEquals('$#!#$',
            $searchengine->additionalHeaders);
        $this->assertEquals(null,
            $searchengine->disabled);
        $this->assertEquals('1',
            $searchengine->hasSiteSearch);
        $this->assertEquals('60',
            $searchengine->cacheDuration);
    }

    // Prüft ob Suchmaschinen korrekt ein- und ausgeschaltet werden können
    public function enablingTest()
    {
        $engines                = simplexml_load_file("tests/testSumas.xml")->xpath("suma");
        $engines[0]['disabled'] = 'next Monday';
        $metager                = new MetaGer();
        $request                = new Request(["focus" => "web"]);
        $metager->parseFormData($request);
        $searchengine = new Bing($engines[0], $metager);

        $this->assertFalse($searchengine->isEnabled());
        $searchengine->enable("tests/testSumas.xml", "enable suma test");
        $this->assertTrue($searchengine->isEnabled());

        $engines = simplexml_load_file("tests/testSumas.xml")->xpath("suma");
        $metager = new MetaGer();
        $request = new Request(["focus" => "web"]);
        $metager->parseFormData($request);
        $searchengine = new Bing($engines[0], $metager);

        $this->assertTrue($searchengine->isEnabled());
    }

    /* Noch fehlende Funktionen (teils kaum testbar)
    abstract public function loadResults($result);
    private function setStatistic($key, $val)
    protected function getHost()
    getNext(MetaGer $metager, $result)
    startSearch(\App\MetaGer $metager)
    rank($eingabe)

    closeFp()
    getSocket()
    retrieveResults(MetaGer $metager)
    shutdown()
    getCurlInfo()
    getCurlErrors()
    addCurlHandle($mh)
    removeCurlHandle($mh)
     */

    /**
     * Funktion zum vereinfachen von Tests, bei denen die Ausgabe einer Funktion einem Object entsprechen soll
     *
     * @param Object    $object              Das Object von dem aus die Funktion aufgerufen werden soll
     * @param String    $funcName            Der Name der Funktion
     * @param array     $input               Die Eingaben für die Funktion
     * @param mixed     $expectedInOutput    Etwas das als Funktionsergebnis erwartet wird (meist ein String)
     */
    public function equalCallbackTester($object, $funcName, $input, $expectedInOutput)
    {
        $output = call_user_func_array(array($object, $funcName), $input);
        $this->assertEquals($expectedInOutput, $output);
    }
}
