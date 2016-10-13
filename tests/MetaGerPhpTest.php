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
        $metager = new MetaGer();
        $request = $this->createDummyRequest();
        $this->fullRunTest($metager, $request);
        $this->specialSearchTest($metager);
        $this->createSearchEnginesTest($metager);
        $this->linkGeneratorTest($metager);
    }

    public function fullRunTest($metager, $request)
    {
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
        $query["eingabe"]     = "garten";
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

    public function specialSearchTest($metager)
    {
        $metager->searchCheckSitesearch("hund katze site:wolf.de", "");
        $this->assertEquals("wolf.de", $metager->getSite());
        $metager->searchCheckHostBlacklist("hund katze -host:wolf.de");
        $this->assertEquals("wolf.de", $metager->getUserHostBlacklist()[0]);
        $metager->searchCheckDomainBlacklist("hund katze -domain:wolf.de");
        $this->assertEquals("wolf.de", $metager->getUserDomainBlacklist()[0]);
        $metager->searchCheckStopwords("hund katze -wolf");
        $this->assertEquals("wolf", $metager->getStopWords()[0]);
        $metager->searchCheckPhrase('hund katze "wolf"');
        $this->assertEquals("wolf", $metager->getPhrases()[0]);
    }

    public function createSearchEnginesTest($metager)
    {
        $this->specialSumaTest($metager);
    }

    public function specialSumaTest($metager)
    {
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

    public function linkGeneratorTest($metager)
    {
        $this->callbackTester($metager, "generateSearchLink", ["bilder"], "http://localhost/meta/meta.ger3?eingabe=garten&focus=bilder&encoding=utf8&lang=all&time=1000&sprueche=on&resultCount=20&tab=on&onenewspage=on&out=results");
        $this->callbackTester($metager, "generateQuicktipLink", [], "http://localhost/qt");
        $this->callbackTester($metager, "generateSiteSearchLink", ["wolf.de"], "http://localhost/meta/meta.ger3?eingabe=garten+site%3Awolf.de&focus=web&encoding=utf8&lang=all&time=1000&sprueche=on&resultCount=20&tab=on&onenewspage=on");
        $this->callbackTester($metager, "generateRemovedHostLink", ["wolf.de"], "http://localhost/meta/meta.ger3?eingabe=garten+-host%3Awolf.de&focus=angepasst&encoding=utf8&lang=all&time=1000&sprueche=on&resultCount=20&tab=on&onenewspage=on");
        $this->callbackTester($metager, "generateRemovedDomainLink", ["wolf.de"], "http://localhost/meta/meta.ger3?eingabe=garten+-domain%3Awolf.de&focus=angepasst&encoding=utf8&lang=all&time=1000&sprueche=on&resultCount=20&tab=on&onenewspage=on");
    }

    public function callbackTester($metager, $funcName, $input, $expectedOutput)
    {
        $output = call_user_func_array(array($metager, $funcName), $input);
        $this->assertEquals($expectedOutput, $output);
    }
}
