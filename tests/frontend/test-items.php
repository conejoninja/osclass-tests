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
        include TEST_ASSETS_PATH . 'ItemData.php';
        $item = $aData[0];

        $this->_userRegistration();

        osc_set_preference('logged_user_item_validation', 1);

        $this->_insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo']);
        $this->assertTrue($this->isTextPresent("Your listing has been published"),"insert ad error ") ;

        $this->_logout();

        $item = $aData[2];


        osc_set_preference('items_wait_time', 0);
        osc_set_preference('selectable_parent_categories', 1);
        osc_set_preference('reg_user_post', 0);
        osc_set_preference('moderate_items', -1);
        //osc_set_preference('logged_user_item_validation', 1);
        
        $this->_insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], $item['contactName'],
            TEST_USER_EMAIL);
        sleep(1);
        $this->assertTrue($this->isTextPresent("A user with that email address already exists, if it is you, please log in"));

        $aItem = Item::newInstance()->listAll();
        foreach($aItem as $item){
            $url = osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] );
            $this->open( $url );
            $this->assertTrue($this->isTextPresent("Your listing has been deleted"), "Delete item.");
        }
    }
    
    function testItems_User()
    {
        include TEST_ASSETS_PATH . 'ItemData.php';
        $item = $aData[0];

        $this->_login();
        osc_set_preference('moderate_items', 0);

        osc_set_preference('logged_user_item_validation', 1);
        osc_reset_preferences();
        $this->_insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo']);
        $this->assertTrue($this->isTextPresent("Your listing has been published"),"insert ad error ") ;

        osc_set_preference('logged_user_item_validation', 0);
        osc_reset_preferences();
        $this->_insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo']);
        $this->assertTrue($this->isTextPresent("Check your inbox to validate your listing"),"Need validation but message don't appear")   ;

    }


    function testAkismet_postItem()
    {
        osc_set_preference('akismetKey', '9f18f856aa3c');
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

        osc_set_preference('items_wait_time', 0);
        osc_set_preference('selectable_parent_categories', 1);
        osc_set_preference('reg_user_post', 0);
        osc_set_preference('moderate_items', -1);

        osc_set_preference('logged_user_item_validation', 0);
        $this->_insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], $item['contactName'],
            $item['contactEmail']);

        $item_id = $this->_lastItemId();

        // check spam detection
        $oItem = Item::newInstance()->findByPrimaryKey($item_id);
        $this->assertTrue($oItem['b_spam']=='1', 'Akismet, detect as spam item.');

        // reset akismet key
        osc_set_preference('akismetKey', '');
        osc_reset_preferences();

        // delete item
        $url = osc_item_delete_url( $oItem['s_secret'] , $oItem['pk_i_id'] );
        $this->open( $url );
        $this->assertTrue($this->isTextPresent("Your listing has been deleted"), "Delete item.");
    }


    function testEditUserItemBadId()
    {
        $this->open( osc_item_edit_url('', '9999') );
        $this->assertTrue($this->isTextPresent("Sorry, we don't have any listings with that ID"));
    }

    function testEditUserItem1()
    {
        //$this->_logout();
        // create new item
        include TEST_ASSETS_PATH . 'ItemData.php';
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

        osc_set_preference('items_wait_time', 0);
        osc_set_preference('selectable_parent_categories', 1);
        osc_set_preference('reg_user_post', 0);
        osc_set_preference('moderate_items', -1);
        osc_set_preference('logged_user_item_validation', 0);
        osc_reset_preferences();

        $this->_insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], $item['contactName'],
            $item['contactEmail']);
        sleep(1);
        $this->assertTrue($this->isTextPresent("Your listing has been published"),"Items, insert item, no user, no validation.") ;

        $itemId = $this->_lastItemId();

        // login and try to edit
        $this->_login();

        $this->open(osc_item_edit_url('', $itemId));
        $this->assertTrue($this->isTextPresent(""),"Sorry, we don't have any listing with that ID") ;

        // remove item
        $_item = Item::newInstance()->findByPrimaryKey($itemId);

        $url = osc_item_delete_url( $_item['s_secret'], $itemId );
        $this->open( $url );
        $this->assertTrue($this->isTextPresent("Your listing has been deleted"), "Delete item.");
    }

    function testActivate()
    {
        $this->_login();

        $this->open( osc_base_url() );
        $this->click("link=My account");
        $this->waitForPageToLoad("30000");

        $this->click("xpath=//span[@class='admin-options']/a[text()='Activate']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue($this->isTextPresent("The listing has been validated"), "Items, validate user item.");
    }

    function testActivate1()
    {

        include TEST_ASSETS_PATH . 'ItemData.php';
        $item = $aData[0];
        osc_set_preference('items_wait_time', 0);
        osc_set_preference('selectable_parent_categories', 1);
        osc_set_preference('reg_user_post', 0);
        osc_set_preference('moderate_items', 111);
        osc_set_preference('logged_user_item_validation', 0);
        osc_reset_preferences();

        $this->_insertItem($item['parentCatId'], $item['catId'], $item['title'],
            $item['description'], $item['price'],
            $item['regionId'], $item['cityId'], $item['cityArea'],
            $item['photo'], 'contact name', 'validate@example.com');

        $itemId = $this->_lastItemId();
        $this->assertTrue($this->isTextPresent("Check your inbox to validate your listing"),"Items, insert item, no user, with validation.") ;


        $this->_login();

        $url = osc_item_activate_url('', $itemId);
        $this->open($url);
        sleep(1);
        // body with class='not-found'
        $count = $this->getXpathCount("//body[contains(@class,'not-found')]");
        $this->assertTrue($count == 1 , "Items, validate item from other user.");
        // 2
        $this->_logout();
        $url = osc_item_activate_url('', $itemId);
        $this->open($url);
        sleep(1);
        $count = $this->getXpathCount("//body[contains(@class,'not-found')]");
        $this->assertTrue($count == 1 , "Items, validate item from no user.");
        // 3
        $item = Item::newInstance()->findByPrimaryKey($itemId);
        $url = osc_item_activate_url($item['s_secret'], $itemId);
        $this->open($url);
        sleep(1);
        $this->assertTrue($this->isTextPresent("The listing has been validated"), "Items, validate item. (direct url)");

        // remove item
        $_item = Item::newInstance()->findByPrimaryKey($itemId);

        $url = osc_item_delete_url( $_item['s_secret'] , $itemId );
        $this->open( $url );
        $this->assertTrue($this->isTextPresent("Your listing has been deleted"), "Delete item.");
    }

    function testEditItem()
    {
        $this->_login();

        osc_set_preference('moderate_items', 0);
        osc_reset_preferences();

        $this->open( osc_base_url() );
        $this->click("link=My account");
        $this->waitForPageToLoad("30000");
        // edit first item
        $this->click("xpath=//span[@class='admin-options']/a[text()='Edit item']");
        $this->waitForPageToLoad("30000");

        $this->select("catId", "label=regexp:\\s*Car Parts");

        $this->type("title[en_US]", "New title new item");
        $this->type("description[en_US]", "New description new item new item new item");
        $this->type("price", "222");
        $this->select("currency", "label=Euro €");
        $this->type('id=region', 'Barcelona');
        $this->type('id=city', 'Sabadell');
        $this->type("cityArea", "New my area");
        $this->type("address", "New my address");
        $this->click("xpath=//button[@type='submit']");
        $this->waitForPageToLoad("3000");

        $this->assertTrue(  $this->isTextPresent("Great! We've just updated your listing"), 'Items, edit first item, with validation.' );

        osc_set_preference('moderate_items', -1);
        osc_reset_preferences();
        $this->open( osc_base_url(true) );
        $this->click("link=My account");
        $this->waitForPageToLoad("30000");
        // edit first item
        $this->click("xpath=//span[@class='admin-options']/a[text()='Edit item']");
        $this->waitForPageToLoad("30000");

        $this->select("catId", "label=regexp:\\s*Car Parts");

        $this->type("title[en_US]", "New title new item NEW ");
        $this->type("description[en_US]", "New description new item new item new item NEW ");
        $this->type("price", "666");
        $this->select("currency", "label=Euro €");
        $this->type('id=region', 'Barcelona');
        $this->type('id=city', 'Sabadell');
        $this->type("cityArea", "New my area");
        $this->type("address", "New my address");
        $this->click("//button[@type='submit']");
        $this->waitForPageToLoad("3000");

        $this->assertTrue( $this->isTextPresent("Great! We've just updated your listing") ,"Items, edit first item, without validation." );

    }

    function testDeleteItemOtherUser()
    {
        //$this->_logout();
        $itemId = $this->_lastItemId();
        $url = osc_item_delete_url('', $itemId);

        $this->open($url);
        $this->assertTrue( $this->isTextPresent("The listing you are trying to delete couldn't be deleted") ,"Items, delete item without secret." );
    }

    function testDeleteItem()
    {
        $this->_login();

        $this->open( osc_base_url() );
        $this->click("link=My account");
        $this->waitForPageToLoad("30000");

        $numItems = $this->getXpathCount("//span[@class='admin-options']/a[text()='Delete']");

        while($numItems > 0) {
            // delete first item
            $this->click("xpath=//span[@class='admin-options']/a[text()='Delete']");
            $this->assertTrue((bool)preg_match('/^This action can not be undone\. Are you sure you want to continue[\s\S]$/',$this->getConfirmation()));
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isTextPresent("Your listing has been deleted"), "Can't delete listing. ERROR ");

            $numItems = $this->getXpathCount("//span[@class='admin-options']/a[text()='Delete']");

            $this->open( osc_base_url() );
            $this->click("link=My account");
            $this->waitForPageToLoad("30000");

            $this->click("link=My account");
            $this->waitForPageToLoad("30000");
        }
        $this->_removeUserByEmail(TEST_USER_EMAIL);
    }


}
?>