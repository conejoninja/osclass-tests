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
        $this->type("contactName" , "contact name");

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

    function _loadItems() {
        require TEST_ASSETS_PATH . 'ItemData.php';

        $old_reg_user_port = osc_get_preference('reg_user_post');
        $old_items_wait_time = osc_get_preference('items_wait_time');
        $old_enabled_recaptcha_items = osc_get_preference('enabled_recaptcha_items');
        $old_moderate_items = osc_get_preference('moderate_items');

        osc_set_preference('reg_user_post', 0);
        osc_set_preference('items_wait_time', 0);
        osc_set_preference('enabled_recaptcha_items', 0);
        osc_set_preference('moderate_items', -1);

        foreach($aData as $item){
            $this->_insertItem(  $item['parentCatId'], $item['catId'], $item['title'],
                $item['description'], $item['price'],
                $item['regionId'], $item['cityId'],  $item['cityArea'],
                $item['photo'], $item['contactName'],
                TEST_USER_EMAIL);
        }

        osc_set_preference('reg_user_post', $old_reg_user_port);
        osc_set_preference('items_wait_time', $old_items_wait_time);
        osc_set_preference('enabled_recaptcha_items', $old_enabled_recaptcha_items);
        osc_set_preference('moderate_items', $old_moderate_items);


    }

    // TODO MERGE THIS WITH _addItem AND DO IT ON THE ADMIN SIDE
    protected  function _insertItem($parentCat, $cat, $title, $description, $price, $regionId, $cityId, $cityArea, $aPhotos, $user = null, $email = null , $logged = 0)
    {

        $this->open( osc_base_url() );
        $this->click("link=Publish your ad for free");
        $this->waitForPageToLoad("30000");
        $this->select("id=catId", "label=regexp:\\s*".$cat);
        sleep(2);
        $this->type("titleen_US", $title);
        $this->type("descriptionen_US", $description);
        $this->type("price", "12".osc_locale_thousands_sep()."34".osc_locale_thousands_sep()."56".osc_locale_dec_point()."78".osc_locale_dec_point()."90");
        $this->fireEvent("price", "blur");
        sleep(2);
        $this->assertTrue($this->getValue("price")=="123456".osc_locale_dec_point()."78", "Check price correction input");
        $this->type("price", $price);
        $this->select("currency", "label=Euro €");
        if($regionId!=NULL) {
            $this->select("countryId", "label=Spain");
            $this->type('id=region', $regionId);
            $this->type('id=city', $cityId);
        }
        if($cityArea==NULL) {
            $this->type("cityArea", "my area");
        } else {
            $this->type("cityArea", $cityArea);
        }
        $this->type("address", "my address");
        if( count($aPhotos) > 0 ) {
            sleep(2);

            $this->chooseOkOnNextConfirmation();
            $this->type("qqfile", TEST_ASSETS_PATH . $aPhotos[0]);
            sleep(4);
            for($k=1;$k<count($aPhotos);$k++) {
                $this->type("qqfile", TEST_ASSETS_PATH . $aPhotos[$k]);
                sleep(4);
            }
        }

        if($user!==null) {
            $this->type("contactName" , $user);
        }
        if($email!==null) {
            $this->type("contactEmail", $email);
        }

        $this->click("//button[@type='submit']");
        $this->chooseOkOnNextConfirmation();
        $this->waitForPageToLoad("30000");
    }


}
