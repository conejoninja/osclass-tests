<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestFrontend.php';
class TestSearch extends OsclassTestFrontend
{

    /*public function testLoadItems()
    {

        osc_set_preference('reg_user_post', 0);
        osc_set_preference('items_wait_time', 0);
        osc_set_preference('enabled_recaptcha_items', 0);
        osc_set_preference('moderate_items', -1);

        include TEST_ASSETS_PATH . 'ItemData.php';

        $k = 0;
        foreach($aData as $item) {

            $this->_insertItem(  $item['parentCatId'], $item['catId'], $item['title'],
                $item['description'], $item['price'],
                $item['regionId'], $item['cityId'],  $item['cityArea'],
                $item['photo'], $item['contactName'],
                TEST_USER_EMAIL);

            $this->assertTrue($this->isTextPresent("Your listing has been published","Insert item.") );

        }
    } */

    /*function testNewly()
    {
        $this->open( osc_search_url() );
        $this->click("link=Newly listed");
        $this->waitForPageToLoad("30000");
        // last item added -> TITLE : SPANISH LESSONS
        $text = $this->getText("xpath=(//div[@class='listing-basicinfo'])[1]");
        $this->assertTrue(1==preg_match('/SPANISH LESSONS/i', $text), "Search, order by Newly");
    }


    function testLowerPrice()
    {
        $this->open( osc_search_url() );
        $this->click("link=Lower price first");
        $this->waitForPageToLoad("30000");
        // last item added -> TITLE : German Training Coordination Agent (Barcelona centre) en Barcelona
        //sleep(4);
        $text = $this->getText("xpath=(//div[@class='listing-basicinfo'])[1]");
        $this->assertTrue(1==preg_match('/German Training Coordination Agent \(Barcelona centre\)/', $text), "Search, order by Lower");
    }

    function testHigherPrice()
    {
        $this->open( osc_search_url() );
        $this->click("link=Higher price first");
        $this->waitForPageToLoad("30000");
        // last item added -> TITLE : Avion ULM TL96 cerca de Biniagual
        //sleep(4);
        $text = $this->getText("xpath=(//div[@class='listing-basicinfo'])[1]");
        $this->assertTrue(1==preg_match('/Avion ULM TL96 cerca de Biniagual/', $text), "Search, order by Higher ");
    }

    function testSPattern()
    {
        $this->open( osc_search_url() );
        $this->type("sPattern", "Moto");
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 4 , "Search by sPattern.");
    }

    function testSPatternCombi1()
    {
        $this->open( osc_search_url() );
        $this->type("sPattern", "Moto");
        $this->type("sPriceMin", "3000");
        $this->type("sPriceMax", "9000");
        // @todo change text by class or id
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");
        sleep(4);
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 2 , "Search by sPattern & pMin - pMax.");
    }

    function testSPatternCombi2()
    {
        $this->open( osc_search_url() );
        $this->type("sPattern", "Moto");
        $this->type("sCity" , "Balsareny");
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 3 , "Search by Moto + sCity = Balsareny.");
    }

    function testSPatternCombi3()
    {
        $this->open( osc_search_url() );
        $this->type("sCity" , "Balsareny");
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 4 , "Search by sCity = Balsareny.");
    }        */

    function _testSPatternCombi4() // TODO FIXME
    {
        $this->open( osc_base_url(true) . "?page=search" );
        $this->click("xpath=//a[@id='cat_1']");
        $this->waitForPageToLoad("30000");
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 1, "Search by sCategory = For sale.");
    }

    /*function testSPatternCombi5()
    {
        $this->open( osc_search_url() );
        $this->click("xpath=//input[@id='withPicture']"); // only items with pictures
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 9 , "Search by [ Show only items with pictures ].");
    }*/

    /*function testSearchUserItems()
    {
        include TEST_ASSETS_PATH . 'ItemData.php';
        osc_set_preference('enabled_user_validation', 0);

        $userId = $this->_userRegistration('testusersearch@osclass.org', 'password');
        // add new items to user
        for($i=0; $i<2; $i++){
            $item = $aData[$i];
            $this->_insertItem(  $item['parentCatId'], $item['catId'], $item['title'],
                $item['description'], $item['price'],
                $item['regionId'], $item['cityId'], $item['cityArea'],
                $item['photo']);
            $this->assertTrue($this->isTextPresent("Your listing has been published", "Insert item.") );
        }

        // check search
        $this->open( osc_search_url(array('sUser' => $userId)) );
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");

        $this->assertTrue($count == 2 , "Search by [ User id ].");

        // remove user test
        User::newInstance()->deleteByPrimaryKey($userId);
    }*/

    /*
    function testLocations()
    {
        $searchCountry  = osc_search_url(array('sCountry'   => 'ES'));
        $this->open( $searchCountry );
        $this->assertTrue( $this->isTextPresent("1 - 12 of 16 listings"), "Insert item." );

        $searchRegion   = osc_search_url(array('sRegion'    => 'Valencia'));
        $this->open( $searchRegion );
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 3 , "Search by [ sRegion Valencia ].");

        $searchCity     = osc_search_url(array('sCity'      => 'Balsareny'));
        $this->open( $searchCity );
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 4 , "Search by [ sCity Balsereny ].");

        $searchCityArea = osc_search_url(array('sCityArea'  => 'city area test'));
        $this->open( $searchCityArea );
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 2 , "Search by [ sCityArea city area test ].");
    }

    function testCreateAlert()
    {
        $this->_createAlert('foobar@invalid_email', false);

        $this->_createAlert(TEST_USER_EMAIL);

        Alerts::newInstance()->delete(array('s_email' => TEST_USER_EMAIL));
    } */

    function testExpiredItems()
    {
        // expire one category (Language Classes)
        $mCategory = new Category();
        $mCategory->update(array('i_expiration_days' => '1') , array('pk_i_id' => '39') );
        // update dt_pub_date
        $mItems = new Item();
        $aItems = $mItems->listWhere('fk_i_category_id = 39');
        foreach($aItems as $actual_item) {
            //echo "update -> " . $actual_item['pk_i_id'] ."<br>";
            $mItems->update( array('dt_expiration' => '2010-05-05 10:00:00', 'dt_pub_date' => '2010-05-03 10:00:00') , array('pk_i_id' => $actual_item['pk_i_id']) );
        }

        Cron::newInstance()->update(array('d_last_exec' => '0000-00-00 00:00:00', 'd_next_exec' => '0000-00-00 00:00:00'), array('e_type' => 'DAILY'));

        $this->open( osc_base_url(true) . "?page=cron" );
        $this->waitForPageToLoad("3000");

        // tests
        // _testMainFrontend();
        $this->open( osc_base_url() );
        $this->assertTrue($this->isTextPresent("Classes (0)"), "Main frontend - category parent of category id 39 have bad counters ERROR" );
        $this->assertTrue($this->isTextPresent("Language Classes (0)"), "Main frontend - category 'Language Classes' (id 39) have bad counters ERROR" );
        // _testSearch();
        $searchCategory = osc_search_url(array('sCategory'  => '3'));
        $this->open( $searchCategory );
        $this->assertTrue($this->isTextPresent("There are no results matching"), "search frontend - there are items ERROR" );
    }

    //
    // añadir test filtros + categoria
    //
     /*
    function testHighligthResults()
    {
        $this->open(osc_search_url() );
        $this->type("sPattern", "http://www.osclass.org");
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");

        // URL Highlight
        $aux = (string)$this->getEval("var win = this.browserbot.getCurrentWindow(); win.document.querySelectorAll('.listing-basicinfo')[0].getElementsByTagName('strong')[0].innerHTML");
        $this->assertTrue( ('http://www.osclass.org' == $aux) , "Highligth url pattern" );
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");

        // THIS COUNT SHOULD BE 1, BUT FULLTEXT SEEMS TO MESS UP SEARCH RESULTS WHEN USING NON NATURAL LANGUAGE, AS URLS
        $this->assertTrue($count == 2 , "Search by [ url pattern ].");

        // pattern with special chars
        $this->open(osc_search_url() );
        $this->type("sPattern", "(osclass)");
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");

        // (Pattern)
        $this->assertTrue( 'osclass' == $this->getEval("var win = this.browserbot.getCurrentWindow(); win.document.querySelectorAll('.listing-basicinfo')[0].getElementsByTagName('strong')[0].innerHTML"), "Highligth (XXX) pattern" );
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 1 , "Search by [ (XXX) pattern ].");
    }

    function testInputEscapeValue()
    {
        $pattern = 'fooo " bar';

        $this->open(osc_search_url() );
        $this->type("sPattern", $pattern );
        $this->type("sCity", $pattern );
        $this->type("sPriceMin", '33');
        $this->type("sPriceMax", '99');
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");

        // value
        $_1 = $this->getEval("var win = this.browserbot.getCurrentWindow(); win.document.getElementsByName('sPattern')[0].value");
        echo "$_1";

        $this->assertTrue( $pattern == $this->getEval("var win = this.browserbot.getCurrentWindow(); win.document.getElementsByName('sPattern')[0].value"), "Correct escape input values sPattern" );
        $this->assertTrue( $pattern ==$this->getEval("var win = this.browserbot.getCurrentWindow(); win.document.getElementsByName('sCity')[0].value"), "Correct escape input values sCity" );
        $this->assertTrue( '33' == $this->getEval("var win = this.browserbot.getCurrentWindow(); win.document.getElementsByName('sPriceMin')[0].value"), "Correct escape input values sPriceMin" );
        $this->assertTrue( '99' == $this->getEval("var win = this.browserbot.getCurrentWindow(); win.document.getElementsByName('sPriceMax')[0].value"), "Correct escape input values sPriceMax" );
    }    */
        /*
    public function testRemoveLoadedItems()
    {
        $aItems = Item::newInstance()->findByEmail(TEST_USER_EMAIL) ;
        foreach( $aItems as $item ) {
            $url = osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] );
            $this->open( $url );
            $this->assertTrue($this->isTextPresent("Your listing has been deleted"), "Delete item.");
        }
    } */

}
?>