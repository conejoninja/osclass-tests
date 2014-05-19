<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminItem extends OsclassTestAdmin
{

    function _testInsertItem()
    {
        $this->_login();
        $this->_insertItem() ;
        $this->_viewMedia_NoMedia();
        $this->_viewComments_NoComments();
        $this->_deactivate();
        $this->_activate();
        $this->_markAsPremium();
        $this->_unmarkAsPremium();
    }

    function _testEditItem()
    {
        $this->_login();
        $this->_editItem();
    }

    function _testDeleteItem()
    {
        $this->_login();
        $this->_deleteItem();
    }

    function testComments()
    {
        $this->_login();
        $this->_insertItemAndComments();
    }

    private function _insertItem($bPhotos = FALSE, $expiration_days = null )
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("xpath=//a[@id='items']");
        $this->waitForPageToLoad("10000");
        $this->click("link=Add new");
        $this->waitForPageToLoad("10000");

        // insert non registered user
        $this->type("contactName" , "contact name");
        $this->type("contactEmail", "test@mail.com");

        $this->select("select_1", "label=regexp:\\s*Vehicles");
        sleep(2);
        $this->select("select_2", "label=regexp:\\s*Cars");
        $this->type("title[en_US]", "title item");
        $this->type("description[en_US]", "description test description test description test");
        $this->type("price", "12".osc_locale_thousands_sep()."34".osc_locale_thousands_sep()."56".osc_locale_dec_point()."78".osc_locale_dec_point()."90");
        $this->fireEvent("price", "blur");
        sleep(2);
        $this->assertTrue($this->getValue("price")=="123456".osc_locale_dec_point()."78", "Check price correction input");
        $this->type("price", "11");
        $this->select("currency", "label=Euro €");

        $this->select("countryId", "label=Spain");

        $this->type('id=region', 'A Coruña');
        //$this->click('id=ui-active-menuitem');

        $this->type('id=city', 'A Capela');
        //$this->click('id=ui-active-menuitem');

        $this->type("address", "address item");

        if( $bPhotos ) {
            $this->type("xpath=//input[@name='photos[]']", LIB_PATH."simpletest/test/osclass/img_test1.gif");
            sleep(0.5);
            $this->type("//div[@id='p-0']/input", LIB_PATH."simpletest/test/osclass/img_test2.gif");
        }

        if( is_numeric($expiration_days) ) {
            $this->type('dt_expiration', $expiration_days);
        }


        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("A new listing has been added"), "Can't insert a new item. ERROR");
    }

    private function _viewMedia_NoMedia()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");
        sleep(2); // time enough to load table data

        $thereIsMedia = (int)$this->getXpathCount("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li/a[text()='View media']");

        $this->assertTrue( ($thereIsMedia==0), "Show media when there aren't. ERROR");
    }

    private function _viewComments_NoComments()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");
        sleep(2); // time enough to load table data

        $thereIsMedia = (int)$this->getXpathCount("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li/a[text()='View comments']");

        $this->assertTrue( ($thereIsMedia==0), "Show media when there aren't. ERROR");
    }

    private function _deactivate()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li[@class='show-more']/ul/li/a[text()='Deactivate']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("The listing has been deactivated"), "Can't deactivate item. ERROR");
    }

    private function _activate()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li[@class='show-more']/ul/li/a[text()='Activate']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("The listing has been activated"), "Can't activate item. ERROR");
    }

    private function _markAsPremium()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li[@class='show-more']/ul/li/a[text()='Mark as premium']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("Changes have been applied"), "Can't mark as premium item. ERROR");
    }

    private function _unmarkAsPremium()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li[@class='show-more']/ul/li/a[text()='Unmark as premium']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("Changes have been applied"), "Can't mark as premium item. ERROR");
    }

    private function _deleteItem()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->click("//table/tbody/tr/td[contains(.,'title_item')]/div/ul/li/a[text()='Delete']");
        $this->click("//input[@id='item-delete-submit']");
        sleep(1);
        $this->waitForPageToLoad("10000");
        sleep(1);
        $this->assertTrue($this->isTextPresent("The listing has been deleted"), "Can't delete item. ERROR");
    }

    private function _editItem()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li/a[text()='Edit']");
        $this->waitForPageToLoad("10000");

        // insert non registered user
        $this->type("contactName" , "contact name_");
        $this->type("contactEmail", "test_@mail.com");

        $this->select("select_1", "label=regexp:\\s*Vehicles");
        $this->select("select_2", "label=regexp:\\s*Cars");
        $this->type("title[en_US]", "title_item");
        $this->type("description[en_US]", "description_test_description test description_test");
        $this->type("price", "11");
        $this->select("currency", "label=Euro €");
        $this->type("region", "A Coruña");
        $this->type("city", "A Capela");
        $this->type("address", "address_item");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("Changes saved correctly"), "Can't edit item. ERROR");
    }

    private function _insertItemAndComments()
    {
        // insert item
        $this->_insertItem() ;

        $mItem = new Item();

        $item = $mItem->findByEmail( 'test@mail.com' );
        $item = $item[0];

        osc_set_preference('enabled_comments', 1);
        osc_set_preference('moderate_comments', 0);
        osc_reset_preferences();
        // insert comment from frontend

        $this->open(osc_item_url_ns( $item['pk_i_id'] ));

        $this->type("authorName"      , "Test B user");
        $this->type("authorEmail"     , "testing+testb@osclass.org");
        $this->type("title"           , "I like it");
        $this->type("body"            , "Can you provide more info please :)");

        $this->click("//form[@id='comment_form']/fieldset/div[@class='actions']/button"); // OJO
        $this->waitForPageToLoad("30000");

        // test oc-admin
        //$this->_login();

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_comments']");
        $this->waitForPageToLoad("10000");

        //$this->mouseOver("//table/tbody/tr/td[contains(text(),'Test B user')]");
        $this->click("//table/tbody/tr/td[contains(text(),'Test B user')]/div/ul/li/a[text()='Activate']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("The comment has been approved"), "Can't activate comment. ERROR" );

        //$this->mouseOver("//table/tbody/tr/td[contains(text(),'Test B user')]");
        $this->click("//table/tbody/tr/td[contains(text(),'Test B user')]/div/ul/li/a[text()='Deactivate']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("The comment has been disapproved"), "Can't deactivate comment. ERROR" );

        //$this->mouseOver("//table/tbody/tr/td[contains(text(),'Test B user')]");
        $this->click("//table/tbody/tr/td[contains(text(),'Test B user')]/div/ul/li/a[text()='Edit']");
        $this->waitForPageToLoad("10000");

        // edit comment
        $this->type("title", "I like it updated");
        $this->type("authorName", "Test user osclass");
        $this->type("body", "Can you provide more info please :) Regards");
        $this->click("xpath=//input[@type='submit']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue($this->isTextPresent("Great! We just updated your comment"), "Can't edit a comment. ERROR") ;

        $this->mouseOver("//table/tbody/tr[contains(text(),'Test B user')]");
        $this->click("//table/tbody/tr[contains(text(),'Test user osclass')]/td/div/ul/li/ul/li/a[text()='Delete']");
        sleep(1);
        $this->click("//input[@id='comment-delete-submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("The comment has been deleted"), "Can't delete a comment. ERROR") ;

        // DELETE ITEM
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        //$this->mouseOver("//table/tbody/tr/td[contains(text(),'title item')]");
        $this->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li/a[text()='Delete']");
        $this->click("//input[@id='item-delete-submit']");

        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("The listing has been deleted"), "Can't delete item. ERROR");
    }

    
    
}
