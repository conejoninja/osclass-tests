<?php

require_once dirname(__FILE__) . '/OsclassTest.php';
require_once TEST_SERVER_PATH . '/oc-load.php';

class OsclassTestAdmin extends OsclassTest
{

    protected function _login($user = TEST_ADMIN_USER, $pass = TEST_ADMIN_PASS)
    {
        $this->open(osc_admin_base_url());
        $this->waitForPageToLoad("30000");
        $this->type("id=user_login", $user);
        $this->type("id=user_pass", $pass);
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
    }

    protected function _logout()
    {
        $this->open(osc_admin_base_url());
        $this->click("link=Logout");
        $this->waitForPageToLoad("30000");
    }

    function _addItem()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("xpath=//a[@id='items']");
        $this->waitForPageToLoad("10000");
        $this->click("link=Add new");
        $this->waitForPageToLoad("10000");

        // insert non registered user
        $this->type("contactName" , "contact name");
        $this->type("contactEmail", "test@mail.com");

        //$this->select("parentCatId", "label=regexp:\\s*Vehicles");
        //$this->select("catId", "label=regexp:\\s*Cars");
        $this->select("select_1", "label=regexp:\\s*Vehicles");
        sleep(2);
        $this->select("select_2", "label=regexp:\\s*Cars");
        $this->type("title[en_US]", "title item");
        $this->type("description[en_US]", "description test description test description test");
        $this->type("price", "11");
        $this->select("currency", "label=Euro €");

        $this->select("countryId", "label=Spain");

        $this->type('id=region', 'A Coruña');
        //$this->click('id=ui-active-menuitem');

        $this->type('id=city', 'A Capela');
        //$this->click('id=ui-active-menuitem');

        $this->type("address", "address item");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("A new listing has been added"), "Can't insert a new item. ERROR");
    }

    function _lastItem()
    {
        $item   = Item::newInstance()->dao->query('select * from '.DB_TABLE_PREFIX.'t_item order by pk_i_id DESC limit 0,1');
        $aItem  = $item->result();
        return $aItem[0];
    }

    function _lastItemId()
    {
        $item = $this->_lastItem();
        return $item['pk_i_id'];
    }


}
