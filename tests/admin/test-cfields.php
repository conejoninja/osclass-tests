<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';

define('MAX_FIELDS', 8);
class TestCFields extends OsclassTestAdmin
{

    function testCustomAdd()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("xpath=//a[@id='items_cfields']");
        $this->waitForPageToLoad("10000");

        // ------------    text    ------------
        $this->click("//a[@id='add-button']");
        sleep(4);
        //$this->selectFrame("edit-custom-field-frame");
        $this->type("s_name", "extra_field_1");
        $this->select("field_type", "TEXT");
        $this->click("//div[@id='advanced_fields_iframe']");
        $this->type('field_slug','my_extra_field');

        $this->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->isTextPresent("extra_field_1"), "Add field");

        // ------------    textarea    ------------
        $this->click("//a[@id='add-button']");
        sleep(4);
        //$this->selectFrame("edit-custom-field-frame");
        $this->type("s_name", "extra_field_2");
        $this->select("field_type", "TEXTAREA");
        $this->click("//div[@id='advanced_fields_iframe']");
        $this->type('field_slug','my_extra_field_2');

        $this->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->isTextPresent("extra_field_2"), "Add field");

        // ------------    DROPDOWN    ------------
        $this->click("//a[@id='add-button']");
        sleep(4);
        //$this->selectFrame("edit-custom-field-frame");
        $this->type("s_name", "extra_field_3");
        $this->select("field_type", "DROPDOWN");
        $this->type("s_options", "");
        $this->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->isTextPresent("At least one option is required"), "Add field check s_option empty");

        $this->type("s_options", "one,two,tree");
        $this->click("//div[@id='advanced_fields_iframe']");
        $this->type('field_slug','my_extra_field_3');

        $this->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->isTextPresent("extra_field_3"), "Add field");

        // ------------    RADIO    ------------
        $this->click("//a[@id='add-button']");
        sleep(4);
        //$this->selectFrame("edit-custom-field-frame");
        $this->type("s_name", "extra_field_4");
        $this->select("field_type", "RADIO");
        $this->type("s_options", "");
        $this->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->isTextPresent("At least one option is required"), "Add field check s_option empty");

        $this->type("s_options", "four, five, six");
        $this->click("//div[@id='advanced_fields_iframe']");
        $this->type('field_slug','my_extra_field_4');

        $this->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->isTextPresent("extra_field_4"), "Add field");

        // ------------    CHECKBOX    ------------
        $this->click("//a[@id='add-button']");
        sleep(4);
        //$this->selectFrame("edit-custom-field-frame");
        $this->type("s_name", "extra_field_5");
        $this->select("field_type", "CHECKBOX");
        $this->type("s_options", "seven, eight, nine");
        $this->click("//div[@id='advanced_fields_iframe']");
        $this->type('field_slug','my_extra_field_5');

        $this->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->isTextPresent("extra_field_5"), "Add field");

        // ------------    URL    ------------
        $this->click("//a[@id='add-button']");
        sleep(4);
        //$this->selectFrame("edit-custom-field-frame");
        $this->type("s_name", "extra_field_6");
        $this->select("field_type", "URL");
        $this->click("//input[@id='field_required']");
        $this->click("//div[@id='advanced_fields_iframe']");
        $this->type('field_slug','my_extra_field_6');

        $this->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->isTextPresent("extra_field_6"), "Add field");

        // ------------    DATE    ------------
        $this->click("//a[@id='add-button']");
        sleep(4);
        //$this->selectFrame("edit-custom-field-frame");
        $this->type("s_name", "extra_field_7");
        $this->select("field_type", "DATE");
        $this->click("//div[@id='advanced_fields_iframe']");
        $this->type('field_slug','my_extra_field_7');

        $this->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->isTextPresent("extra_field_7"), "Add field");

        // ------------    DATEINTERVAL    ------------
        $this->click("//a[@id='add-button']");
        sleep(4);
        //$this->selectFrame("edit-custom-field-frame");
        $this->type("s_name", "extra_field_8");
        $this->select("field_type", "DATE INTERVAL");
        $this->click("//div[@id='advanced_fields_iframe']");
        $this->type('field_slug','my_extra_field_8');

        $this->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->isTextPresent("extra_field_8"), "Add field");

    }


    function testCustomEdit()
    {
        $this->_login();

        $this->open( osc_admin_base_url(true) );
        $this->click("xpath=//a[@id='items_cfields']");
        $this->waitForPageToLoad("10000");


        for($k=MAX_FIELDS;$k>0;$k--) {
            $this->click("xpath=(//div[@class='cfield-div']/div[@class='actions-edit-cfield']/a[contains(.,'Edit')])[".$k."]");
            sleep(4);
            // check all
            $this->click("link=Check all");
            sleep(4);
            $this->assertTrue($this->isChecked("categories[]"), "Check all categories" );
            // make all custom fields searchables
            $this->click("//div[@id='advanced_fields_iframe']");
            $this->click("//input[@id='field_searchable']");
            $this->click("//input[@type='submit']");
            sleep(4);
            $this->assertTrue($this->isTextPresent("Saved"), "Edit field");
        }
    }

    function testCustomOnWebsite()
    {
        $this->_login();
        $this->_customOnFrontEnd();
        $this->_customOnAdminPanel();
    }

    function testCustomSearch()
    {
//        search via custom fields
        $this->_login();
//        TEXT  --
        $this->open( osc_search_url(array('sCategory' => array('1'))) );
        $this->waitForPageToLoad("30000");

        $this->type("id=meta_my_extra_field", "ocadmincustom2");
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 1 , "Search by custom field - TEXT.");
//        TEXTAREA --
        $this->open( osc_search_url(array('sCategory' => array('1'))) );
        $this->waitForPageToLoad("30000");

        $this->type("id=meta_my_extra_field_2", "ocadmincustom3");
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 1 , "Search by custom field - TEXTAREA.");
//        URL --
        $this->open( osc_search_url(array('sCategory' => array('1'))) );
        $this->waitForPageToLoad("30000");

        $this->type("id=meta_my_extra_field_6", "ocadmincustom6");
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 1 , "Search by custom field - URL.");
//        RADIO BUTTON --
        $this->open( osc_search_url(array('sCategory' => array('1'))) );
        $this->waitForPageToLoad("30000");

        $this->select("id=meta_my_extra_field_4", "four");
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 1 , "Search by custom field - RADIO BUTTON.");
//        CHECKBOX
        $this->open( osc_search_url(array('sCategory' => array('1'))) );
        $this->waitForPageToLoad("30000");

        $this->click("id=meta_my_extra_field_5");
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 1 , "Search by custom field - CHECKBOX BUTTON.");
//        DATE
        $this->open( osc_search_url(array('sCategory' => array('1'))) );
        $this->waitForPageToLoad("30000");
        $d1  = '1367359200';
        $this->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementById('meta_my_extra_field_7').value = '".$d1."'; }");
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 1 , "Search by custom field - DATE.");
//        DATEINTERVAL
        $this->open( osc_search_url(array('sCategory' => array('1'))) );
        $this->waitForPageToLoad("30000");
        $d1  = '1367704800';  // May 5, 2013
        $d2  = '1369173599';  // May 21, 2013
        $this->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementById('meta_my_extra_field_8_from').value = '".$d1."'; }");
        $this->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementById('meta_my_extra_field_8_to').value = '".$d2."'; }");
        $this->click("xpath=//button[text()='Apply']");
        $this->waitForPageToLoad("30000");
        $count = $this->getXpathCount("//li[contains(@class,'listing-card')]");
        $this->assertTrue($count == 1 , "Search by custom field - DATE INTERVAL.");
    }

    function testDeleteAllItems()
    {
        $this->_login();
        // search through custom fields
        $this->_deleteAllItems();
    }

    /**
     * delete custom fields
     */
    function testCustomDelete()
    {
        $this->_login();

        $this->open( osc_admin_base_url(true) );
        $this->click("xpath=//a[@id='items_cfields']");
        $this->waitForPageToLoad("10000");

        for($k=MAX_FIELDS;$k>0;$k--) {
            $this->click("xpath=(//div[@class='cfield-div']/div[@class='actions-edit-cfield']/a[contains(.,'Delete')])[1]");
            sleep(2);
            $this->click("//a[@id='field-delete-submit']");
            sleep(3);
            $this->assertTrue($this->isTextPresent("The custom field has been deleted"), "Delete field");
            sleep(2);
        }
    }

    private function _customOnFrontEnd()
    {
        osc_set_preference('reg_user_post', 0);
        osc_set_preference('moderate_items', -1);

        // check if custom fields appears at website
        $this->open( osc_base_url(true) );
        $this->waitForPageToLoad("10000");
        $this->click("link=Publish your ad for free");
        $this->waitForPageToLoad("10000");

        $this->select("catId", "label=regexp:\\s*Animals");
        sleep(2);
        $this->type("id=titleen_US", "foo title");
        $this->type("id=descriptionen_US","description foo title");
        $this->select("countryId", "label=Spain");
        $this->type("region", "Albacete");
        $this->type("city", "Albacete");
        $this->type("cityArea", "my area");
        $this->type("address", "my address");

        $this->type('id=contactName' , 'foobar');
        $this->type('id=contactEmail', 'foobar@mail.com');

        $this->assertTrue($this->isTextPresent("extra_field_1")    , "Custom fields at frontend");
        $this->assertTrue($this->isTextPresent("extra_field_2")    , "Custom fields at frontend");
        $this->assertTrue($this->isTextPresent("extra_field_3")    , "Custom fields at frontend");

        /**
         * DATE / DATEINTERVAL Notes:
         *
         * May 1, 2013  -> 1367359200
         * May 30, 2013 -> 1369864800
         */

        $d1  = '1367359200';
        $d2  = '1369864800';

        // DATE
        $this->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementById('meta_my_extra_field_7').value = '".$d1."'; }");
        // DATE INTERVAL
        $this->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementById('meta_my_extra_field_8_from').value = '".$d1."'; }");
        $this->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementById('meta_my_extra_field_8_to').value = '".$d2."'; }");

        $this->type("id=meta_my_extra_field"  , "custom2");
        $this->type("id=meta_my_extra_field_2"  , "custom3");
        // radio button value = five
        $this->click("id=meta_my_extra_field_4_1");

        $this->click("//button[text()='Publish']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("extra_field_6 field is required.","Field required") );

        sleep(3);
        $this->type("id=meta_my_extra_field_6"      , "custom6");

        $this->click("//button[text()='Publish']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("Your listing has been published","Item published") );

        // remove item
        Item::newInstance()->delete( array('s_contact_email' => 'foobar@mail.com') ) ;
    }

    private function _customOnAdminPanel()
    {
        // check if custom fields appears at website
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("link=Add new");
        $this->waitForPageToLoad("10000");

        $this->select("select_1", "label=regexp:\\s*For sale");
        sleep(2);
        $this->select("select_2", "label=regexp:\\s*Animals");
        sleep(2);
        $this->type("id=title[en_US]", "foo title");
        $this->type("id=description[en_US]","description foo title");
        $this->select("countryId", "label=Spain");
        $this->type("region", "Albacete");
        $this->type("city", "Albacete");
        $this->type("cityArea", "my area");
        $this->type("address", "my address");

        $this->type('id=contactName' , 'foobar');
        $this->type('id=contactEmail', 'foobar@mail.com');

        $this->assertTrue($this->isTextPresent("extra_field_1"), "Custom fields at ocadmin");
        $this->assertTrue($this->isTextPresent("extra_field_2")    , "Custom fields at ocadmin");
        $this->assertTrue($this->isTextPresent("extra_field_3")    , "Custom fields at ocadmin");

        /**
         * DATE / DATEINTERVAL Notes:
         *
         * May 12, 2013  -> 1368309600
         * May 18, 2013 -> 1368914399
         */

        $d1  = '1368309600';
        $d2  = '1368914399';

        // DATE
        $this->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementById('meta_my_extra_field_7').value = '".$d1."'; }");
        // DATE INTERVAL
        $this->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementById('meta_my_extra_field_8_from').value = '".$d1."'; }");
        $this->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementById('meta_my_extra_field_8_to').value = '".$d2."'; }");


        $this->type("id=meta_my_extra_field"  , "ocadmincustom2");
        $this->type("id=meta_my_extra_field_2"  , "ocadmincustom3");
        $this->select("id=meta_my_extra_field_3"  , "two");

        // radio button value = four
        $this->click("id=meta_my_extra_field_4_0");
        // check checkbox
        $this->click("id=meta_my_extra_field_5");


        $this->click("//input[@value='Add listing']");
        $this->waitForPageToLoad("10000");
        sleep(3);
        $this->assertTrue($this->isTextPresent("extra_field_6 field is required.","Field required") );

        $this->type("id=meta_my_extra_field_6"      , "ocadmincustom6");

        $this->click("//input[@value='Add listing']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("A new listing has been added"),"Item published" );
    }


    private function _deleteAllItems()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");

        $this->click("//input[@id='check_all']");
        $this->select("//select[@name='bulk_actions']", "label=Delete");
        $this->click("//input[@id='bulk_apply']");
        sleep(2);
        $this->click("//a[@id='bulk-actions-submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue($this->isTextPresent( "regexpi:listings have been deleted"));
    }


}
