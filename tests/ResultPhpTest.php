<?php

use App\MetaGer;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultPhpTest extends TestCase
{
    // Die Testfunktion die PHP Unit aufruft
    // Ruft alle anderen Untertests auf
    public function test()
    {
        $this->rankingTest();
        $this->isValidTest();
        $this->linkGeneratorsTest();
    }

    // Liefert ein standard Suchergebnis
    public function getDummyResult()
    {
        $provider    = file_get_contents("tests/testSumas.xml");
        $titel       = "Titel";
        $link        = "link.de";
        $anzeigeLink = "link.de/anzeige";
        $descr       = "Beschreibung: i want phrase
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";
        $gefVon     = "";
        $sourceRank = 1;
        return new Result($provider, $titel, $link, $anzeigeLink, $descr, $gefVon, $sourceRank);
    }

    // Liefert eine standard MetaGer
    public function getDummyMetaGer()
    {
        $metager = new MetaGer();
        $request = $this->createDummyRequest();
        $metager->parseFormData($request);
        $metager->checkSpecialSearches($request);
        return $metager;
    }

    // Liefert eine standard Suchanfrage
    public function createDummyRequest()
    {
        $query                = [];
        $query["eingabe"]     = 'suchwort -blackword -host:blackhost -domain:blackdomain site:wantsite "i want phrase"';
        $query["focus"]       = "angepasst";
        $query["encoding"]    = "utf8";
        $query["lang"]        = "all";
        $query["time"]        = "1000";
        $query["sprueche"]    = "on";
        $query["resultCount"] = "20";
        $query["tab"]         = "on";
        $query["onenewspage"] = "on";

        return new Request($query);
    }

    // Testet ob das Ranking nicht übermäßig vom gewünschten Wert abweicht
    public function rankingTest()
    {
        $result = $this->getDummyResult();
        $result->rank("link"); # 0.38, 0.38512820512820511 mit url-boost auch bei description länge > 0 statt > 80
        $this->assertEquals(0.4, $result->getRank(), "Not within Range of Actual Value", 0.1);
        $result->rank("titel"); # 0.38419999999999999
        $this->assertEquals(0.4, $result->getRank(), "Not within Range of Actual Value", 0.1);
        $result->rank("beschreibung"); # 0.38280000000000003
        $this->assertEquals(0.4, $result->getRank(), "Not within Range of Actual Value", 0.1);
        $result->rank("baum"); # 0.38
        $this->assertEquals(0.4, $result->getRank(), "Not within Range of Actual Value", 0.1);
    }

    // Prüft die Valid funktion, die für Ergebnisse auf der Host- oder Domain-Blacklist false zurückgeben soll
    public function isValidTest()
    {
        $result  = $this->getDummyResult();
        $metager = $this->getDummyMetaGer();
        $this->assertTrue($result->isValid($metager));

        $metager = new MetaGer();
        $request = new Request(['eingabe' => 'test -site:host.domain.de -site:*.domain.de']);
        $metager->parseFormData($request);
        $metager->checkSpecialSearches($request);

        $provider    = file_get_contents("tests/testSumas.xml");
        $titel       = "Titel";
        $link        = "host.domain.de";
        $anzeigeLink = "host.domain.de/ergebnis/1?p=2";
        $descr       = "Beschreibung: i want phrase";
        $gefVon      = "";
        $sourceRank  = 1;

        $result = new Result($provider, $titel, $link, $anzeigeLink, $descr, $gefVon, $sourceRank);
        $this->assertFalse($result->isValid($metager));

        $link = "domain.de/ergebnis/1?p=2";

        $result = new Result($provider, $titel, $link, $anzeigeLink, $descr, $gefVon, $sourceRank);
        $this->assertFalse($result->isValid($metager));
    }

    // Prüft die Funktionen die Links umformen oder erzeugen
    public function linkGeneratorsTest()
    {
        $result = $this->getDummyResult();
        $this->equalCallbackTester($result, "getStrippedHost", ["http://www.foo.bar.de/test?ja=1"],
            'foo.bar.de');
        $this->equalCallbackTester($result, "getStrippedLink", ["http://www.foo.bar.de/test?ja=1"],
            'foo.bar.de/test');
        $this->equalCallbackTester($result, "getStrippedDomain", ["http://www.foo.bar.de/test?ja=1"],
            'bar.de');
        $this->equalCallbackTester($result, "generateProxyLink", ["http://www.foo.bar.de/test?ja=1"],
            'https://proxy.suma-ev.de/cgi-bin/nph-proxy.cgi/en/I0/http/www.foo.bar.de/test?ja=1');

        $url = "https://leya:organa@www.han.solo.de/unterseite/document.htm?param1=2&param2=1#siebzehn";

        $this->equalCallbackTester($result, "getStrippedHost", [$url],
            'han.solo.de');
        $this->equalCallbackTester($result, "getStrippedDomain", [$url],
            'solo.de');
        $this->equalCallbackTester($result, "getStrippedLink", [$url],
            'han.solo.de/unterseite/document.htm');
    }

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
