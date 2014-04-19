<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestFrontend.php';
class TestItems extends OsclassTestFrontend
{

    public function testNoUser()
    {

        osc_set_preference('items_wait_time', 0);
        osc_set_preference('selectable_parent_categories', 1);
        osc_set_preference('reg_user_post', 0);
        osc_set_preference('moderate_items', -1);

        include TEST_ASSETS_PATH . 'ItemData.php';
        $item = $aData[0];

        $this->_insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], $item['contactName'],
            TEST_USER_EMAIL);
        $this->assertTrue($this->isTextPresent("Your listing has been published"),"Items, insert item, no user, no validation.") ;

        osc_set_preference('moderate_items', 111);
        $this->_insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], $item['contactName'],
            TEST_USER_EMAIL);
        $this->assertTrue($this->isTextPresent("Check your inbox"),"Items, insert item, no user, with validation.") ;

        osc_set_preference('reg_user_post', 1);
        $this->open( osc_base_url() );
        $this->click("link=Publish your ad for free");
        $this->waitForPageToLoad("30000");
        $this->assertTrue($this->isTextPresent("Only registered users are allowed to post listings"), "Items, insert item, no user, can't publish");


        $aItem = Item::newInstance()->listAll('s_contact_email = ' . TEST_USER_EMAIL . ' AND fk_i_user IS NULL');
        foreach($aItem as $item){
            $url = osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] );
            $this->open( $url );
            $this->assertTrue($this->isTextPresent("Your listing has been deleted"), "Delete item.");
        }
    }


    function testItems_useExistingEmail()
    {

        osc_set_preference('items_wait_time', 0);
        osc_set_preference('selectable_parent_categories', 1);
        osc_set_preference('reg_user_post', 0);
        osc_set_preference('moderate_items', -1);

        include TEST_ASSETS_PATH . 'ItemData.php';
        $item = $aData[0];

        $uSettings = new utilSettings();
        $old_enabled_users              = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation    = $uSettings->set_enabled_user_validation(0);

        $this->doRegisterUser();
        $this->loginWith();

        $old_logged_user_item_validation = $uSettings->set_logged_user_item_validation(1);
        $this->insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], $item['contactName'],
            $this->_email);
        $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"insert ad error ") ;

        $uSettings->set_logged_user_item_validation( $old_logged_user_item_validation );
        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);

        // try to insert an item with existing user email
        $this->logout();

        $item = $aData[2];

        $uSettings = new utilSettings();
        $items_wait_time                  = $uSettings->set_items_wait_time(0);
        $set_selectable_parent_categories = $uSettings->set_selectable_parent_categories(1);
        $bool_reg_user_post               = $uSettings->set_reg_user_post(0);
        $bool_enabled_user_validation     = $uSettings->set_moderate_items(-1);

        $old_logged_user_item_validation = $uSettings->set_logged_user_item_validation(1);
        $this->insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], $item['contactName'],
            $this->_email);
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("A user with that email address already exists, if it is you, please log in"));

        $uSettings->set_items_wait_time($items_wait_time);
        $uSettings->set_selectable_parent_categories($set_selectable_parent_categories);
        $uSettings->set_reg_user_post($bool_reg_user_post);
        $uSettings->set_moderate_items($bool_enabled_user_validation);

        /*
         * Remove all items inserted previously
         */
        $aItem = Item::newInstance()->listAll();
        foreach($aItem as $item){
            $url = osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] );
            $this->selenium->open( $url );
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been deleted"), "Delete item.");
        }
    }
    /*
    function testItems_User()
    {
        require dirname(__FILE__).'/ItemData.php';
        $item = $aData[0];

        $uSettings = new utilSettings();
        $old_moderate_items             = $uSettings->set_moderate_items(0);
        $old_enabled_users              = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation    = $uSettings->set_enabled_user_validation(0);
        osc_reset_preferences();

        $this->doRegisterUser();
        $this->loginWith();


        $old_logged_user_item_validation = $uSettings->set_logged_user_item_validation(1);
        osc_reset_preferences();
        $this->insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], $item['contactName'],
            $this->_email);
        $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"insert ad error ") ;

        $uSettings->set_logged_user_item_validation(0);
        osc_reset_preferences();
        $this->insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], $item['contactName'],
            $this->_email);
        $this->assertTrue($this->selenium->isTextPresent("Check your inbox to validate your listing"),"Need validation but message don't appear")   ;

        $uSettings->set_moderate_items($old_moderate_items);
        $uSettings->set_logged_user_item_validation( $old_logged_user_item_validation );
        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);

        unset($uSettings);
    }

    function testAkismet_postItem()
    {
        // add akismet keys
        Preference::newInstance()->update(array('s_value' => '9f18f856aa3c') ,array('s_name'  => 'akismetKey'));
        osc_reset_preferences();

        // add spam item
        $item = array(
            "parentCatId"   => 'Vehicles',
            "catId"         => 'Cars',
            'title'         => '2000 Ford Focus',
            'description'   => '2000 Ford Focus ZX3 Hatchback 2D Good Condition Clean Great Car Mileage: 175000 Passed BMV Emissions Clear Title Call me or Text if interested- Crystal 219',
            'price'         => '101',
            'regionId'      => 'Barcelona'  ,'cityId'        => 'Terrassa',
            'cityArea'      => ''           ,'address'       => '',
            'photo'         => array(),
            'contactName'   => 'viagra-test-123',
            'contactEmail'  => 'new@email.com'
        );

        $uSettings = new utilSettings();
        $items_wait_time                  = $uSettings->set_items_wait_time(0);
        $set_selectable_parent_categories = $uSettings->set_selectable_parent_categories(1);
        $bool_reg_user_post               = $uSettings->set_reg_user_post(0);
        $bool_enabled_user_validation     = $uSettings->set_moderate_items(-1);

        $old_logged_user_item_validation = $uSettings->set_logged_user_item_validation(1);
        $this->insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], $item['contactName'],
            $item['contactEmail']);

        $item_id = $this->_lastItemId();

        // check spam detection
        $oItem = Item::newInstance()->findByPrimaryKey($item_id);
        $this->assertTrue($oItem['b_spam']=='1', 'Akismet, detect as spam item.');

        // reset akismet key
        Preference::newInstance()->update(array('s_value' => '') ,array('s_name'  => 'akismetKey'));
        osc_reset_preferences();

        // delete item
        $url = osc_item_delete_url( $oItem['s_secret'] , $oItem['pk_i_id'] );
        $this->selenium->open( $url );
        $this->assertTrue($this->selenium->isTextPresent("Your listing has been deleted"), "Delete item.");
    }

    function testEditUserItemBadId()
    {
        $this->selenium->open( osc_item_edit_url('', '9999') );
        $this->assertTrue($this->selenium->isTextPresent("Sorry, we don't have any listings with that ID"));
    }

    function testEditUserItem1()
    {
        $this->logout();
        // create new item
        require dirname(__FILE__).'/ItemData.php';
        $item = array(
            "parentCatId"   => 'Vehicles',
            "catId"         => 'Cars',
            'title'         => '2000 Ford Focus',
            'description'   => '2000 Ford Focus ZX3 Hatchback 2D Good Condition Clean Great Car Mileage: 175000 Passed BMV Emissions Clear Title Call me or Text if interested- Crystal 219',
            'price'         => '101',
            'regionId'      => 'Barcelona'  ,'cityId'        => 'Terrassa',
            'cityArea'      => ''           ,'address'       => '',
            'photo'         => array(),'contactName'   => 'contact ad 1','contactEmail'  => 'new@email.com'
        );

        $uSettings = new utilSettings();
        $items_wait_time                  = $uSettings->set_items_wait_time(0);
        $set_selectable_parent_categories = $uSettings->set_selectable_parent_categories(1);
        $bool_reg_user_post               = $uSettings->set_reg_user_post(0);
        $bool_enabled_user_validation     = $uSettings->set_moderate_items(-1);

        $old_logged_user_item_validation = $uSettings->set_logged_user_item_validation(1);
        $this->insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], $item['contactName'],
            $item['contactEmail']);
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"Items, insert item, no user, no validation.") ;

        $uSettings->set_items_wait_time($items_wait_time);
        $uSettings->set_selectable_parent_categories($set_selectable_parent_categories);
        $uSettings->set_reg_user_post($bool_reg_user_post);
        $uSettings->set_moderate_items($bool_enabled_user_validation);
        $itemId = $this->_lastItemId();

        // login and try to edit
        $this->loginWith();

        $this->selenium->open(osc_item_edit_url('', $itemId));
        $this->assertTrue($this->selenium->isTextPresent(""),"Sorry, we don't have any listing with that ID") ;

        // remove item
        $_item = Item::newInstance()->findByPrimaryKey($itemId);

        $url = osc_item_delete_url( $_item['s_secret'], $itemId );
        $this->selenium->open( $url );
        $this->assertTrue($this->selenium->isTextPresent("Your listing has been deleted"), "Delete item.");
    }

    function testActivate() // Activate
    {
        $this->loginWith();

        $this->selenium->open( osc_base_url() );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->click("xpath=//span[@class='admin-options']/a[text()='Activate']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("The listing has been validated"), "Items, validate user item.");
    }

    function testActivate1()
    {
        $uSettings = new utilSettings();
        $old_enabled_users              = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation    = $uSettings->set_enabled_user_validation(0);

        $this->doRegisterUser();
//        $uSettings->set_logged_user_item_validation( $old_logged_user_item_validation );
        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);

        $itemId = $this->_insertItemToValidate();

        // 1
        $this->loginWith();
        $url = osc_item_activate_url('', $itemId);
        $this->selenium->open($url);
        sleep(1);
        // body with class='not-found'
        $count = $this->selenium->getXpathCount("//body[contains(@class,'not-found')]");
        $this->assertTrue($count == 1 , "Items, validate item from other user.");
        // 2
        $this->logout();
        $url = osc_item_activate_url('', $itemId);
        $this->selenium->open($url);
        sleep(1);
        $count = $this->selenium->getXpathCount("//body[contains(@class,'not-found')]");
        $this->assertTrue($count == 1 , "Items, validate item from no user.");
        // 3
        $item = Item::newInstance()->findByPrimaryKey($itemId);
        $url = osc_item_activate_url($item['s_secret'], $itemId);
        $this->selenium->open($url);
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("The listing has been validated"), "Items, validate item. (direct url)");

        // remove item
        $_item = Item::newInstance()->findByPrimaryKey($itemId);

        $url = osc_item_delete_url( $_item['s_secret'] , $itemId );
        $this->selenium->open( $url );
        $this->assertTrue($this->selenium->isTextPresent("Your listing has been deleted"), "Delete item.");
    }

    function testEditItem()
    {
        $this->loginWith();

        sleep(5);

        $uSettings = new utilSettings();
        $old_moderate_items = $uSettings->set_moderate_items(0);

        $this->selenium->open( osc_base_url() );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");
        // edit first item
        $this->selenium->click("xpath=//span[@class='admin-options']/a[text()='Edit item']");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->select("catId", "label=regexp:\\s*Car Parts");

        $this->selenium->type("title[en_US]", "New title new item");
        $this->selenium->type("description[en_US]", "New description new item new item new item");
        $this->selenium->type("price", "222");
        $this->selenium->select("currency", "label=Euro €");
        $this->selenium->type('id=region', 'Barcelona');
        $this->selenium->click('id=ui-active-menuitem');
        $this->selenium->type('id=city', 'Sabadell');
        $this->selenium->click('id=ui-active-menuitem');
        $this->selenium->type("cityArea", "New my area");
        $this->selenium->type("address", "New my address");
        $this->selenium->click("xpath=//button[@type='submit']");
        $this->selenium->waitForPageToLoad("3000");

        $this->assertTrue(  $this->selenium->isTextPresent("Great! We've just updated your listing"), 'Items, edit first item, with validation.' );

        $old_moderate_items = $uSettings->set_moderate_items(-1);
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");
        // edit first item
        $this->selenium->click("xpath=//span[@class='admin-options']/a[text()='Edit item']");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->select("select_2", "label=regexp:\\s*Car Parts");

        $this->selenium->type("title[en_US]", "New title new item NEW ");
        $this->selenium->type("description[en_US]", "New description new item new item new item NEW ");
        $this->selenium->type("price", "666");
        $this->selenium->select("currency", "label=Euro €");
        $this->selenium->type('id=region', 'Barcelona');
        $this->selenium->click('id=ui-active-menuitem');
        $this->selenium->type('id=city', 'Sabadell');
        $this->selenium->click('id=ui-active-menuitem');
        $this->selenium->type("cityArea", "New my area");
        $this->selenium->type("address", "New my address");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("3000");

        $this->assertTrue( $this->selenium->isTextPresent("Great! We've just updated your listing") ,"Items, edit first item, without validation." );

        $uSettings->set_moderate_items($old_moderate_items);

        unset($uSettings);
    }

    function testDeleteItemOtherUser()
    {
        $this->logout();
        $itemId = $this->_lastItemId();
        $url = osc_item_delete_url('', $itemId);

        $this->selenium->open($url);
        $this->assertTrue( $this->selenium->isTextPresent("The listing you are trying to delete couldn't be deleted") ,"Items, delete item without secret." );
    }

    function testDeleteItem()
    {
        $this->loginWith();

        $this->selenium->open( osc_base_url() );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");

        $numItems = $this->selenium->getXpathCount("//span[@class='admin-options']/a[text()='Delete']");

        while($numItems > 0) {
            // delete first item
            $this->selenium->click("xpath=//span[@class='admin-options']/a[text()='Delete']");
            $this->selenium->waitForPageToLoad("30000");
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been deleted"), "Can't delete listing. ERROR ");

            $numItems = $this->selenium->getXpathCount("//span[@class='admin-options']/a[text()='Delete']");

            $this->selenium->open( osc_base_url() );
            $this->selenium->click("link=My account");
            $this->selenium->waitForPageToLoad("30000");

            $this->selenium->click("link=My account");
            $this->selenium->waitForPageToLoad("30000");
        }
        $this->removeUserByMail();
    }

    function _insertItemToValidate()
    {
        $this->logout();
        require dirname(__FILE__).'/ItemData.php';
        $item = $aData[3];

        $uSettings = new utilSettings();
        $items_wait_time                  = $uSettings->set_items_wait_time(0);
        $set_selectable_parent_categories = $uSettings->set_selectable_parent_categories(1);
        $bool_reg_user_post               = $uSettings->set_reg_user_post(0);
        $bool_enabled_user_validation     = $uSettings->set_moderate_items(2);

        $old_logged_user_item_validation = $uSettings->set_logged_user_item_validation(1);
        $this->insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], $item['contactName'],
            'test@force.com');
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("Check your inbox to validate your listing"),"Items, insert item, no user, with validation.") ;

        $uSettings->set_items_wait_time($items_wait_time);
        $uSettings->set_selectable_parent_categories($set_selectable_parent_categories);
        $uSettings->set_reg_user_post($bool_reg_user_post);
        $uSettings->set_moderate_items($bool_enabled_user_validation);

        return $this->_lastItemId();
    }
                 */



}
?>