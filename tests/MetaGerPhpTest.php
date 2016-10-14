<?php

use App\MetaGer;
use Illuminate\Http\Request;

class MetaGerPhpTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test()
    {
        $this->fullRunTest();
        $this->specialSearchTest();
        $this->specialSumaTest();
        $this->linkGeneratorTest();
        #$this->getHostCountTest();
        $this->addLinkTest();
        $this->adjustFocusTest();
        $this->checkCanNotSitesearchTest();
        $this->isBildersucheTest();
        $this->loadMiniSucherTest();
        $this->getImageProxyLinkTest();
        $this->showQuicktipsTest();
        # Brauchen Engine Dummy
        #$this->popAdTest();
        #$this->productsTest();
    }

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

    public function specialSearchTest()
    {
        $metager = new MetaGer();
        $request = new Request(['eingabe' => 'suchwort -blackword -host:blackhost -domain:blackdomain site:wantsite "i want phrase"']);
        $metager->parseFormData($request);
        $metager->checkSpecialSearches($request);
        $this->assertEquals("wantsite", $metager->getSite());
        $this->assertEquals("blackhost", $metager->getUserHostBlacklist()[0]);
        $this->assertEquals("blackdomain", $metager->getUserDomainBlacklist()[0]);
        $this->assertEquals("blackword", $metager->getStopWords()[0]);
        $this->assertEquals("i want phrase", $metager->getPhrases()[0]);
    }

    public function addLinkTest()
    {
        $metager = new MetaGer();
        $link    = "metager.de";
        $this->assertTrue($metager->addLink($link));
        $this->assertFalse($metager->addLink($link));
    }

    public function specialSumaTest()
    {
        $metager      = new MetaGer();
        $suma         = new SimpleXMLElement("<suma></suma>");
        $suma["name"] = "qualigo";
        $this->assertTrue($metager->sumaIsAdsuche($suma, false));
        $suma["disabled"] = "1";
        $this->assertTrue($metager->sumaIsDisabled($suma));
        $suma["name"] = 'overture';
        $this->assertTrue($metager->sumaIsOverture($suma));
        $suma["name"] = 'bing';
        $this->assertTrue($metager->sumaIsNotAdsuche($suma));
    }

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
            '-host%3Awolf.de');
        $this->containCallbackTester($metager, "generateRemovedDomainLink", ["wolf.de"],
            '-domain%3Awolf.de');
    }

    public function containCallbackTester($object, $funcName, $input, $expectedInOutput)
    {
        $output = call_user_func_array(array($object, $funcName), $input);
        $this->assertContains($expectedInOutput, $output);
    }

    public function getHostCountTest()
    {
        $metager = new MetaGer();
        $before  = $metager->getHostCount("host.de");
        $metager->addHostCount("host.de");
        $after = $metager->getHostCount("host.de");
        $this->assertEquals($before + 1, $after);
        $before = $after;
        $metager->addHostCount("host.de");
        $after = $metager->getHostCount("host.de");
        $this->assertEquals($before + 1, $after);
    }

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
    }

    public function checkCanNotSitesearchTest()
    {
        $metager              = new MetaGer();
        $enabledSearchengines = simplexml_load_file("tests/testSumas.xml")->xpath("suma"); # Eine spezielle test sumas.xml
        $this->assertFalse($metager->checkCanNotSitesearch($enabledSearchengines));
    }

    public function isBildersucheTest()
    {
        $metager = new MetaGer();
        $request = new Request(["focus" => "bilder"]);
        $metager->parseFormData($request);
        $this->assertTrue($metager->isBildersuche());
    }

    public function loadMiniSucherTest()
    {
        $metager        = new MetaGer();
        $sumas          = simplexml_load_file("tests/testSumas.xml");
        $subcollections = ["minism1", "minism2"];
        $minisucher     = $metager->loadMiniSucher($sumas, $subcollections);
        $this->assertContains("rows=10", $minisucher["formData"]->__toString());
        $this->assertContains("fq=subcollection:%28minism1+OR+minism2%29", $minisucher["formData"]->__toString());
    }

    public function getImageProxyLinkTest()
    {
        $metager = new MetaGer();
        $this->containCallbackTester($metager, "getImageProxyLink", ["www.bilder.de/bild1.png"], "url=www.bilder.de%2Fbild1.png");
    }

    public function showQuicktipsTest()
    {
        $metager = new MetaGer();
        $request = new Request(["quicktips" => "yo"]);
        $metager->parseFormData($request);
        $this->assertFalse($metager->showQuicktips());
    }

    public function popAdTest()
    {
        $metager = new MetaGer();
        $this->assertNull($metager->popAd());
        $engines   = [];
        $engines[] = factory(app\Models\parserSkripte\Base::class)->make([], null);
        $metager->combineResults($engines);
        $ad = $metager->popAd();
        $this->assertNull($metager->popAd());
    }

    public function productsTest()
    {
        $metager = new MetaGer();
        $metager->hasProducts();
        $metager->getProducts();
    }
}
