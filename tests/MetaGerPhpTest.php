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
        $this->createSearchEnginesTest();
        $this->linkGeneratorTest();
        #$this->getHostCountTest();
        $this->addLinkTest();
        $this->adjustFocusTest();
        $this->checkCanNotSitesearchTest();
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

    public function createSearchEnginesTest()
    {
        $this->specialSumaTest();
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
        $this->linkCallbackTester($metager, "generateSearchLink", ["news"],
            '/^.*?eingabe=test&focus=news&out=results$/');
        $this->linkCallbackTester($metager, "generateQuicktipLink", [],
            '/\/qt/');
        $this->linkCallbackTester($metager, "generateSiteSearchLink", ["wolf.de"],
            '/^.*?eingabe=test\+site%3Awolf.de&focus=web$/');
        $this->linkCallbackTester($metager, "generateRemovedHostLink", ["wolf.de"],
            '/^.*?eingabe=test\+-host%3Awolf.de$/');
        $this->linkCallbackTester($metager, "generateRemovedDomainLink", ["wolf.de"],
            '/^.*?eingabe=test\+-domain%3Awolf.de$/');
    }

    public function linkCallbackTester($metager, $funcName, $input, $expectedOutput)
    {
        $output = call_user_func_array(array($metager, $funcName), $input);
        $this->assertRegExp($expectedOutput, $output);
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
}
