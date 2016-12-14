<?php

use App\MetaGer;
use App\models\parserSkripte\RlvProduct;
use Illuminate\Http\Request;

class MetaGerPhpTest extends TestCase
{
    // Die Testfunktion die PHP Unit aufruft
    // Ruft alle anderen Untertests auf
    public function test()
    {
        $this->fullRunTest();
        $this->specialSearchTest();
        $this->specialSumaTest();
        $this->linkGeneratorTest();
        $this->getHostCountTest();
        $this->addLinkTest();
        $this->adjustFocusTest();
        $this->checkCanNotSitesearchTest();
        $this->isBildersucheTest();
        $this->loadMiniSucherTest();
        $this->getImageProxyLinkTest();
        $this->showQuicktipsTest();
        $this->popAdTest();
        $this->productsTest();
    }

    // Führt alle Schritte einer normalen MetaGer Suche durch
    // Es werden keine bestimmten Werte erwartet, nur dass das Programm nicht abstürzt
    public function fullRunTest()
    {
        $metager = new MetaGer();
        $request = $this->createDummyRequest();
        $metager->parseFormData($request);
        $metager->checkSpecialSearches($request);
        $metager->createSearchEngines($request);
        $metager->rankAll();
        $metager->prepareResults();
        $metager->createView();
    }

    // Testet das erkennen von Spezialsuchen in verschiedenen Sucheingaben
    public function specialSearchTest()
    {
        $metager = $this->createSpecialSearchMetager('suchwort -blackword -site:blackhost -site:*.blackdomain site:wantsite "i want phrase"');
        $this->assertEquals("wantsite", $metager->getSite());
        $this->assertContains("blackhost", $metager->getUserHostBlacklist());
        $this->assertContains("blackdomain", $metager->getUserDomainBlacklist());
        $this->assertContains("blackword", $metager->getStopWords());
        $this->assertContains("i want phrase", $metager->getPhrases());

        $metager = $this->createSpecialSearchMetager('site:peter:test -blackword-test -site:blackhost-test.de.nz/test ich suche nach -site:blackhost:blackhost2.cote/t?p=5 "peter ist obst-garten und -bauern"');
        $this->assertEquals("peter:test", $metager->getSite());
        $this->assertContains("blackhost:blackhost2.cote/t?p=5", $metager->getUserHostBlacklist());
        $this->assertContains("blackhost-test.de.nz/test", $metager->getUserHostBlacklist());
        $this->assertContains("blackword-test", $metager->getStopWords());
        $this->assertNotContains("bauern", $metager->getStopWords());
        $this->assertContains("peter ist obst-garten und -bauern", $metager->getPhrases());

        $metager = $this->createSpecialSearchMetager('-site:-site:*.test');
        $this->assertContains("-site:*.test", $metager->getUserHostBlacklist());

        $metager = $this->createSpecialSearchMetager('"-site:-site:*.test"');
        $this->assertContains("-site:-site:*.test", $metager->getPhrases());

        $metager = $this->createSpecialSearchMetager('site:wikipedia.de apfel site:test.de tomate');
        $this->assertEquals("apfel tomate", $metager->getQ());
        $this->assertEquals("test.de", $metager->getSite());

        $metager = $this->createSpecialSearchMetager('site:wikipedia.de');
        $this->assertEquals("", $metager->getQ());
        $this->assertEquals("wikipedia.de", $metager->getSite());
    }

    public function createSpecialSearchMetager($eingabe)
    {
        $metager = new MetaGer();
        $request = new Request(['eingabe' => $eingabe]);
        $metager->parseFormData($request);
        $metager->checkSpecialSearches($request);
        return $metager;
    }

    // Testet, ob ein Link wirklich nur einmal hinzugefügt werden kann
    public function addLinkTest()
    {
        $metager = new MetaGer();
        $link    = "metager.de";
        $this->assertTrue($metager->addLink($link));
        $this->assertFalse($metager->addLink($link));
    }

    // Testet die Funktionen die spezielle Sumas filtern
    public function specialSumaTest()
    {
        $metager = new MetaGer();
        $suma    = new SimpleXMLElement("<suma></suma>");

        $suma["name"] = "qualigo";
        $this->assertTrue($metager->sumaIsAdsuche($suma, false));
        $suma["name"] = "similar_product_ads";
        $this->assertTrue($metager->sumaIsAdsuche($suma, false));
        $suma["name"] = "rlvproduct";
        $this->assertTrue($metager->sumaIsAdsuche($suma, false));
        $suma["name"] = "overtureAds";
        $this->assertTrue($metager->sumaIsAdsuche($suma, false));
        $suma["name"] = "overtureAds";
        $this->assertFalse($metager->sumaIsAdsuche($suma, true));
        $suma["name"] = "bing";
        $this->assertFalse($metager->sumaIsAdsuche($suma, false));

        $this->assertFalse($metager->sumaIsDisabled($suma));
        $suma["disabled"] = "0";
        $this->assertFalse($metager->sumaIsDisabled($suma));
        $suma["disabled"] = "1";
        $this->assertTrue($metager->sumaIsDisabled($suma));

        $suma["name"] = 'overture';
        $this->assertTrue($metager->sumaIsOverture($suma));
        $suma["name"] = 'overtureAds';
        $this->assertTrue($metager->sumaIsOverture($suma));
        $suma["name"] = 'bing';
        $this->assertFalse($metager->sumaIsOverture($suma));

        $suma["name"] = 'qualigo';
        $this->assertFalse($metager->sumaIsNotAdsuche($suma));
        $suma["name"] = 'similar_product_ads';
        $this->assertFalse($metager->sumaIsNotAdsuche($suma));
        $suma["name"] = 'overtureAds';
        $this->assertFalse($metager->sumaIsNotAdsuche($suma));
        $suma["name"] = 'bing';
        $this->assertTrue($metager->sumaIsNotAdsuche($suma));
    }

    // Testet die Generatoren für spezielle Links
    public function linkGeneratorTest()
    {
        $metager = new Metager();
        $request = new Request(['eingabe' => 'test']);
        $metager->parseFormData($request);
        $this->containCallbackTester($metager, "generateSearchLink", ["news"],
            'focus=news');
        $this->containCallbackTester($metager, "generateQuicktipLink", [],
            '/qt');
        $this->containCallbackTester($metager, "generateSiteSearchLink", ["wolf.de"],
            'site%3Awolf.de');
        $this->containCallbackTester($metager, "generateRemovedHostLink", ["wolf.de"],
            '-site%3Awolf.de');
        $this->containCallbackTester($metager, "generateRemovedDomainLink", ["wolf.de"],
            '-site%3A%2A.wolf.de');
    }

    // Prüft ob der Host Count funktioniert
    public function getHostCountTest()
    {
        $metager = new MetaGer();
        $host    = "host.de";
        $before  = $metager->getHostCount($host);
        $metager->addHostCount($host);
        $after = $metager->getHostCount($host);
        $this->assertEquals($before + 1, $after);
        $before = $after;
        $metager->addHostCount($host);
        $after = $metager->getHostCount($host);
        $this->assertEquals($before + 1, $after);
    }

    // Prüft ob bei passender Einstellung der Sumas der Fokus automatisch umgestellt wird
    public function adjustFocusTest()
    {
        $metager = new MetaGer();
        $request = new Request(["focus" => "web"]);
        $metager->parseFormData($request);
        $this->assertEquals("web", $metager->getFokus());
        $sumas                = simplexml_load_file("tests/testSumas.xml")->xpath("suma"); # Eine spezielle test sumas.xml
        $enabledSearchengines = $sumas;
        $metager->adjustFocus($sumas, $enabledSearchengines);
        $this->assertEquals("bilder", $metager->getFokus());

        $metager = new MetaGer();
        $request = new Request(["focus" => "web"]);
        $metager->parseFormData($request);
        $this->assertEquals("web", $metager->getFokus());
        $sumas                = simplexml_load_file("tests/testSumas2.xml")->xpath("suma"); # Eine spezielle test sumas.xml
        $enabledSearchengines = array_slice($sumas, 0, 1);
        $metager->adjustFocus($sumas, $enabledSearchengines);
        $this->assertEquals("bilder", $metager->getFokus());
    }

    // Prüft ob das fehlen einer Suchmaschine mit Seitensuche erkannt wird
    public function checkCanNotSitesearchTest()
    {
        $metager              = new MetaGer();
        $enabledSearchengines = simplexml_load_file("tests/testSumas.xml")->xpath("suma"); # Eine spezielle test sumas.xml
        $this->assertFalse($metager->checkCanNotSitesearch($enabledSearchengines));

        $metager = new MetaGer();
        $request = $this->createDummyRequest();
        $metager->parseFormData($request);
        $metager->checkSpecialSearches($request);
        $this->assertEquals("wantsite", $metager->getSite());
        $enabledSearchengines = simplexml_load_file("tests/testSumas2.xml")->xpath("suma"); # Eine spezielle test sumas.xml
        $this->assertTrue($metager->checkCanNotSitesearch($enabledSearchengines));
    }

    // Prüft ob Bildersuchen erkannt werden
    public function isBildersucheTest()
    {
        $metager = new MetaGer();
        $request = new Request(["focus" => "bilder"]);
        $metager->parseFormData($request);
        $this->assertTrue($metager->isBildersuche());

        $metager = new MetaGer();
        $request = new Request(["focus" => "web"]);
        $metager->parseFormData($request);
        $this->assertFalse($metager->isBildersuche());
    }

    // Prüft ob der Link für Minisucher richtig erstellt wird
    public function loadMiniSucherTest()
    {
        $metager        = new MetaGer();
        $sumas          = simplexml_load_file("tests/testSumas.xml");
        $subcollections = ["minism1", "minism2"];
        $minisucher     = $metager->loadMiniSucher($sumas, $subcollections);
        $this->assertContains("rows=10", $minisucher["formData"]->__toString());
        $this->assertContains("fq=subcollection:%28minism1+OR+minism2%29", $minisucher["formData"]->__toString());
    }

    // Prüft ob der Link für den Image Proxy richtig erstellt wird
    public function getImageProxyLinkTest()
    {
        $metager = new MetaGer();
        $this->containCallbackTester($metager, "getImageProxyLink", ["www.bilder.de/bild1.png"], "url=www.bilder.de%2Fbild1.png");
    }

    // Prüft ob sich Quicktips korrekt ein und ausschalten lassen
    public function showQuicktipsTest()
    {
        $metager = new MetaGer();
        $request = new Request(["quicktips" => "yo"]);
        $metager->parseFormData($request);
        $this->assertFalse($metager->showQuicktips());

        $metager = new MetaGer();
        $request = new Request([]);
        $metager->parseFormData($request);
        $this->assertTrue($metager->showQuicktips());
    }

    // Prüft ob Werbung der Werbeliste hinzugefügt wird und ob die pop-funktion für die Werbeergebnisse funktioniert
    public function popAdTest()
    {
        $metager = new MetaGer();
        $this->assertNull($metager->popAd());
        $engineList = [];

        $engineXml = simplexml_load_file("tests/testSumas.xml")->xpath("suma");
        $metager   = new MetaGer();
        $request   = new Request(["focus" => "web"]);
        $metager->parseFormData($request);
        $searchengine = new RlvProduct($engineXml[0], $metager);
        $product      = new \App\Models\Result(
            $engineXml[0],
            "Werbetitel",
            "Link",
            "Anzeigelink",
            "Beschreibung",
            "Gefunden Von",
            42,
            false,
            "image.png",
            4.2,
            "Additional Info"
        );
        $searchengine->ads[] = $product;

        $enginesList[] = $searchengine;
        $metager->combineResults($enginesList, $metager);
        $this->assertEquals("Werbetitel", $metager->popAd()['titel']);
    }

    // Prüft ob Produktergebnisse der Produktliste hinzugefügt werden
    public function productsTest()
    {
        $metager = new MetaGer();
        $this->assertFalse($metager->hasProducts());
        $this->assertEmpty($metager->getProducts());
        $engineList = [];

        $engineXml = simplexml_load_file("tests/testSumas.xml")->xpath("suma");
        $metager   = new MetaGer();
        $request   = new Request(["focus" => "web"]);
        $metager->parseFormData($request);
        $searchengine = new RlvProduct($engineXml[0], $metager);
        $product      = new \App\Models\Result(
            $engineXml[0],
            "Produkttitel",
            "Link",
            "Anzeigelink",
            "Beschreibung",
            "Gefunden Von",
            42,
            false,
            "image.png",
            4.2,
            "Additional Info"
        );
        $searchengine->products[] = $product;

        $enginesList[] = $searchengine;
        $metager->combineResults($enginesList, $metager);
        $this->assertTrue($metager->hasProducts());
        $this->assertEquals("Produkttitel", $metager->getProducts()[0]['titel']);
    }

    // Erstellt eine Suchanfrage zu Testzwecken
    public function createDummyRequest()
    {
        /**
         * Constructor.
         *
         * @param array           $query      The GET parameters
         * @param array           $request    The POST parameters
         * @param array           $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
         * @param array           $cookies    The COOKIE parameters
         * @param array           $files      The FILES parameters
         * @param array           $server     The SERVER parameters
         * @param string|resource $content    The raw body data
         */

        #new Request(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)

        $query                = [];
        $query["eingabe"]     = 'suchwort -blackword -site:blackhost -site:*.blackdomain site:wantsite "i want phrase"';
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

    /**
     * Funktion zum vereinfachen von Tests, bei denen die Ausgabe einer Funktion ein bestimmtes Objekt enthalten soll
     *
     * @param Object    $object              Das Object von dem aus die Funktion aufgerufen werden soll
     * @param String    $funcName            Der Name der Funktion
     * @param array     $input               Die Eingaben für die Funktion
     * @param mixed     $expectedInOutput    Etwas das im Funktionsergebnis erwartet wird (meist ein String)
     */
    public function containCallbackTester($object, $funcName, $input, $expectedInOutput)
    {
        $output = call_user_func_array(array($object, $funcName), $input);
        $this->assertContains($expectedInOutput, $output);
    }
}
