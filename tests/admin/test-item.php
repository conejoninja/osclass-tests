<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminItem extends OsclassTestAdmin
{

    function testInsertItem()
    {
        $this->_login();
        $this->_insertItem2() ;
        $this->_viewMedia_NoMedia();
        $this->_viewComments_NoComments();
        $this->_deactivate();
        $this->_activate();
        $this->_markAsPremium();
        $this->_unmarkAsPremium();
    }

    function testEditItem()
    {
        $this->_login();
        $this->_editItem();
    }

    function testDeleteItem()
    {
        $this->_login();
        $this->_deleteItem();
    }

    function testComments()
    {
        $this->_login();
        $this->_insertItemAndComments();
    }

    function testMedia()
    {
        $this->_login();
        $this->_insertItemAndMedia();
    }

    function testSettings()
    {
        $this->_login();
        $this->_settings();
    }


    private function _insertItem2($bPhotos = FALSE, $expiration_days = null )
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
            $this->type("xpath=//input[@name='photos[]']", TEST_ASSETS_PATH . 'img_test1.gif');
            $this->keyUp("xpath=//input[@name='photos[]']", 'a');
            sleep(3);
            $this->type("//div[@id='p-0']/input", TEST_ASSETS_PATH . 'img_test2.gif');
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

        $this->click("//table/tbody/tr[contains(.,'title_item')]/td/div/ul/li/a[text()='Delete']");
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
        $this->_insertItem2() ;

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

        //$this->mouseOver("//table/tbody/tr[contains(text(),'Test B user')]");
        $this->click("//table/tbody/tr/td[contains(text(),'Test user osclass')]/div/ul/li/ul/li/a[text()='Delete']");
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
        $this->click("//table/tbody/tr[contains(.,'title item')]/td/div/ul/li/a[text()='Delete']");
        $this->click("//input[@id='item-delete-submit']");

        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("The listing has been deleted"), "Can't delete item. ERROR");
    }

    private function _insertItemAndMedia()
    {
        // insert item
        $this->_insertItem2( TRUE ) ;

        // test oc-admin
        //$this->loginWith();

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_media']");
        $this->waitForPageToLoad("10000");
//        $this->assertTrue($this->isTextPresent("Showing 1 to 2 of 2 entries"), "Inconsistent . ERROR" );

        // only can delete resources
        $this->click("xpath=//a[position()=1 and contains(.,'Delete')]");
        sleep(4);
        $this->click("//input[@id='media-delete-submit']");
        $this->waitForPageToLoad("10000");
        sleep(20);
        $this->assertTrue($this->isTextPresent("Resource deleted"), "Can't delete media. ERROR" );
//        $this->assertTrue($this->isTextPresent("Showing 1 to 1 of 1 entries"), "Can't delete media. ERROR" );

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_media']");
        $this->waitForPageToLoad("10000");

        $this->click("xpath=//a[position()=1 and contains(.,'Delete')]");
        sleep(4);
        $this->click("//input[@id='media-delete-submit']");
        $this->waitForPageToLoad("10000");
        sleep(20);
        $this->assertTrue($this->isTextPresent("Resource deleted"), "Can't delete media. ERROR" );
        $this->assertTrue($this->isTextPresent("No data available in table"), "Can't delete media. ERROR" );

        // DELETE ITEM
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");

        //$this->mouseOver("//table/tbody/tr/td[contains(text(),'title item')]");
        $this->click("//table/tbody/tr[contains(.,'title item')]/td/div/ul/li/a[text()='Delete']");
        $this->click("//input[@id='item-delete-submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("The listing has been deleted"), "Can't delete item. ERROR");
    }

    private function _settings()
    {
        osc_set_preference('reg_user_post', 0);
        $this->_logout();
        $this->_checkWebsite_reg_user_post(0);
        $this->_checkWebsite_reg_user_post(0,true);
        osc_set_preference('reg_user_post', 1);
        $this->_checkWebsite_reg_user_post(1);
        osc_set_preference('logged_user_item_validation', 1);
        $this->_checkWebsite_reg_user_post(1,true);

        osc_set_preference('reg_user_post', 0);
        osc_set_preference('enabled_recaptcha_items', 1);
        $this->_checkWebsite_recaptcha(1);
        osc_set_preference('enabled_recaptcha_items', 0);
        $this->_checkWebsite_recaptcha(0);

        osc_set_preference('logged_user_item_validation', 0);
        osc_set_preference('moderate_items', 1);
        $this->_checkWebsite_moderate_items('1');

        osc_set_preference('moderate_items', -1);
        $this->_checkWebsite_moderate_items('-1');

        osc_set_preference('moderate_items', 0);
        $this->_checkWebsite_moderate_items('0');
// logged_user_item_validation
        osc_set_preference('logged_user_item_validation', 0);
        $this->_checkWebsite_logged_user_item_validation('0');
        osc_set_preference('logged_user_item_validation', 1);
        $this->_checkWebsite_logged_user_item_validation('1');
// items_wait_time
        osc_set_preference('items_wait_time', 0);
        $this->_checkWebsite_items_wait_time('0');
        $this->deleteAllVisibleCookies();
        osc_set_preference('items_wait_time', 30);
        $this->_checkWebsite_items_wait_time('30');
// reg_user_can_contact
        osc_set_preference('items_wait_time', 0);
        osc_set_preference('reg_user_can_contact', 0);
        $this->_checkWebsite_reg_user_can_contact('0');
        usleep(25000);
        osc_set_preference('reg_user_can_contact', 1);
        $this->_checkWebsite_reg_user_can_contact('1');
// enableField#f_price@items
        osc_set_preference('enableField#f_price@items', 0);
        $this->_checkWebsite_enableField_f_price_items('0');
        usleep(25000);
        osc_set_preference('enableField#f_price@items', 1);
        $this->_checkWebsite_enableField_f_price_items('1');
// enableField#images@items  //  numImages@items
        osc_set_preference('enableField#f_price@items', 0);
        $this->_checkWebsite_enableField_images_items('0');
        osc_set_preference('enableField#f_price@items', 1);
        osc_set_preference('numImages@items', 1);
        $this->_checkWebsite_enableField_images_items('1','1');
        osc_set_preference('numImages@items', 4);

        $this->_login();

        $mItem = new Item();
        $aItems = $mItem->listAll();
        foreach($aItems as $item) {
            $res = $mItem->deleteByPrimaryKey($item['pk_i_id']);
        }
        $user = User::newInstance()->findByEmail(TEST_USER_EMAIL);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    private function _checkWebsite_reg_user_post($bool,$loginUser = false)
    {
        if($loginUser){
            $this->_addUserForTesting();
            $this->_loginWebsite();
        } else {
            $this->_logOutWebsite();
        }

        if($bool == 0) {
            $this->_post_item_website($loginUser);
            $this->assertTrue($this->isTextPresent("Your listing has been published") || $this->isTextPresent('Check your inbox to validate your listing'),"Can post an item (all can post items). ERROR" ) ;
        } else if($bool == 1 && !$loginUser) {
            $this->open(osc_base_url(true) );
            // i need click twice, if not don't appear flash message
            $this->click("xpath=//a[text()='Publish your ad for free']");
            $this->waitForPageToLoad("30000");
            $this->click("xpath=//a[text()='Publish your ad for free']");
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isTextPresent("Only registered users are allowed to post listings"),"No user can post a item. ERROR" ) ;
        } else if($bool == 1 && $loginUser) {
            $this->_post_item_website($loginUser);
            $this->assertTrue($this->isTextPresent("Your listing has been published") || $this->isTextPresent('Check your inbox to verify your listing'),"User cannot post an item. ERROR" ) ;
        }

        if($loginUser){
            // detele user and items
            $user = User::newInstance()->findByEmail(TEST_USER_EMAIL);
            User::newInstance()->deleteUser($user['pk_i_id']);
        } else {
            // delete items
            Item::newInstance()->delete(array( 's_contact_name' => 'foobar') );
        }
    }

    private function _addUserForTesting()
    {
        $user = User::newInstance()->findByEmail(TEST_USER_EMAIL);

        if(isset($user['pk_i_id']) ) {
            User::newInstance()->deleteUser($user['pk_i_id']);
        }

        $input['s_secret']          = osc_genRandomPassword() ;
        $input['dt_reg_date']       = date('Y-m-d H:i:s');
        $input['s_name']            = "Carlos";
        $input['s_website']         = "www.osclass.org";
        $input['s_phone_land']      = "931234567";
        $input['s_phone_mobile']    = "666121212";
        $input['fk_c_country_code'] = null ;
        $input['s_country']         = null ;
        $input['fk_i_region_id']    = null ;
        $input['s_region']          = "" ;
        $input['fk_i_city_id']      = null ;
        $input['s_city']            = "";
        $input['s_city_area']       = "";
        $input['s_address']         = "c:/address nº 10 2º2ª";
        $input['b_company']         = 0;
        $input['b_enabled']         = 1;
        $input['b_active']          = 1;
        $input['s_email']           = TEST_USER_EMAIL;

        $input['s_password']        = sha1(TEST_USER_PASS);

        $this->array = $input;

        User::newInstance()->insert($input) ;
    }

    private function _loginWebsite($email = TEST_USER_EMAIL, $pass = TEST_USER_PASS)
    {
        $this->open(osc_base_url());
        $bool = $this->isElementPresent('login_open') ;
        if($bool){
            $this->click("id=login_open");
            $this->waitForPageToLoad("30000");
            $this->type("id=email", $email);
            $this->type("id=password", $pass);
            $this->click("//button[@type='submit']");
            $this->waitForPageToLoad("30000");
            if($this->isTextPresent("Logout")){
                $this->logged = 1;
                $this->assertTrue(true, "Login website");
            }else {
                $this->assertTrue(false, "Login website");
            }
        }
    }

    private function _logOutWebsite()
    {
        $this->open( osc_base_url(true) );
        $bool = $this->isElementPresent('login_open') ;
        if(!$bool) {
            $this->click("link=Logout");
            $this->waitForPageToLoad("30000");
        }
    }

    private function _post_item_website($loggedUser = false)
    {
        $this->open( osc_item_post_url() );

        $this->select("catId", "label=regexp:\\s*Animals");
        $this->type("id=titleen_US", "foo title");
        $this->type("id=descriptionen_US","description foo title");
        $this->select("countryId", "label=Spain");
        $this->type("region", "Albacete");
        $this->type("city", "Albacete");
        $this->type("cityArea", "my area");
        $this->type("address", "my address");

        if(!$loggedUser) {
            $this->type('id=contactName' , TEST_USER_USER);
            $this->type('id=contactEmail', TEST_USER_EMAIL);
        }

        $this->click("xpath=//button[text()='Publish']");
        $this->waitForPageToLoad("30000");
    }

    private function _checkWebsite_recaptcha($bool)
    {
        // spam & boots -> fill  private & public keys
        $this->_login();
        $this->open( osc_admin_base_url(true) .'?page=settings&action=spamNbots' );
        $this->type('recaptchaPubKey', '6Lc5PsQSAAAAAEWQYBh5X7pepBL1FuYvdhEFTk0v') ;
        $this->type('recaptchaPrivKey' , '6Lc5PsQSAAAAADnbAmtxG_kfwIxPikL-mjSMyv22');
        $this->click("//input[@id='submit_recaptcha']");
        $this->waitForPageToLoad("10000");

        // test website
        $this->open( osc_item_post_url() );
        $exist_recaptcha = $this->isElementPresent("//table[@id='recaptcha_table']");

        // recaptcha enabled
        if($bool == 1){
            $this->assertTrue($exist_recaptcha, "Recaptcha is not present ! ERROR") ;
            // recaptcha disabled
        } else {
            $this->assertTrue(!$exist_recaptcha, "Recaptcha is present ! ERROR") ;
        }

        //$this->_login();
        $this->open( osc_admin_base_url(true) .'?page=settings&action=spamNbots' );
        $this->type('recaptchaPubKey', '') ;
        $this->type('recaptchaPrivKey' , '');
        $this->click("//input[@id='submit_recaptcha']");
        $this->waitForPageToLoad("10000");
        $this->_logOutWebsite();
    }

    private function _checkWebsite_moderate_items($moderation, $user = 1)
    {
        // create user
        $this->_addUserForTesting();
        // loginWebsite
        $this->_loginWebsite();
        //$this->_login(TEST_USER_EMAIL, TEST_USER_PASS);

        $this->_post_item_website(true);
        if($moderation == -1) {
            $this->assertTrue($this->isTextPresent("Your listing has been published"),"Item need validation moderate_items = -1 (NEVER MODERATE). ERROR" );
        } else if($moderation == 0 || $moderation == 1) {
            $this->assertTrue($this->isTextPresent("Check your inbox to validate your listing"),"Need validation but message don't appear") ;
            // fake validate item
            $user = User::newInstance()->findByEmail(TEST_USER_EMAIL);
            $new_i_item = $user['i_items']+1;
            User::newInstance()->update(array('i_items' => $new_i_item), array('pk_i_id' => $user['pk_i_id']));
        }

        $this->_post_item_website(true);
        if($moderation == -1 || $moderation == 1) {
            $this->assertTrue($this->isTextPresent("Your listing has been published"),"Item need validation moderate_items = -1 (".$moderation.") (NEVER MODERATE). ERROR" );
        } else if($moderation == 0) {
            $this->assertTrue($this->isTextPresent("Check your inbox to validate your listing"),"Need validation but message don't appear" );
        }

        $user = User::newInstance()->findByEmail(TEST_USER_EMAIL);
        User::newInstance()->deleteUser($user['pk_i_id']);

    }

    private function _checkWebsite_logged_user_item_validation($bool)
    {
        // create user
        $this->_addUserForTesting();
        // loginWebsite
        $this->_loginWebsite();
        // force validation
        osc_set_preference('moderate_items', 0);
        osc_reset_preferences();
        // add new item
        $this->_post_item_website(true);

        if($bool == 0){
            $this->assertTrue($this->isTextPresent("Check your inbox to validate your listing"),"Need validation but message don't appear" );
        } else {
            $this->assertTrue($this->isTextPresent("Your listing has been published"),"Item need validation moderate_items = -1 (NEVER MODERATE). ERROR" );
        }

        $user = User::newInstance()->findByEmail(TEST_USER_EMAIL);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    private function _checkWebsite_items_wait_time($sec)
    {
        // create user
        $this->_addUserForTesting();
        // loginWebsite
        $this->_loginWebsite();
        osc_set_preference('moderate_items', '-1');
        osc_reset_preferences();
        if($sec == 0){
            $this->_post_item_website(true);
            $this->assertTrue($this->isTextPresent("Your listing has been published"),"Cannot insert item. ERROR" );
            $this->_post_item_website(true);
            $this->assertTrue($this->isTextPresent("Your listing has been published"),"Cannot insert item. ERROR" );
        } else if($sec > 0) {
            sleep($sec+5);
            $this->_post_item_website(true);
            $this->assertTrue($this->isTextPresent("Your listing has been published"),"Cannot insert item. ERROR" );
            $this->_post_item_website(true);
            $this->assertTrue($this->isTextPresent("Too fast. You should wait a little to publish your ad."),"CAN insert item. ERROR" );
        }

        $user = User::newInstance()->findByEmail(TEST_USER_EMAIL);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    private function _checkWebsite_reg_user_can_contact($bool)
    {
        // create user
        $this->_addUserForTesting();
        // loginWebsite
        $this->_loginWebsite();
        
        osc_set_preference('moderate_items', '-1');
        osc_reset_preferences();

        $this->_post_item_website(true);
        // ir a search

        $this->open( osc_base_url(true) );
        $this->click('link=Logout');
        $this->waitForPageToLoad("10000");
        $this->open( osc_search_url() );
        // visit fisrt item
        $this->click('link=foo title');
        $this->waitForPageToLoad("10000");

        $div_present = $this->isElementPresent("xpath=//div[@id='contact']/form[@name='contact_form']");

        if($bool == 1){
            $this->assertFalse($div_present, "There are form contact_form form. ERROR");
        } else if($bool == 0) {
            $this->assertTrue($div_present, "There aren't form contact_form form. ERROR");
        }

        $user = User::newInstance()->findByEmail($this->_email);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    private function _checkWebsite_enableField_f_price_items( $bool )
    {
        $this->_addUserForTesting();
        // loginWebsite
        $this->_loginWebsite();

        osc_set_preference('moderate_items', '-1');
        osc_reset_preferences();
        // check item_post()
        $this->open( osc_item_post_url() );
        $exist_input_price = $this->isElementPresent("xpath=//input[@id='price']") ;

        if($bool == 1){
            $this->assertTrue($exist_input_price, "Not exist input price!. ERROR");
        } else {
            $this->assertTrue(!$exist_input_price, "Exist input price!. ERROR");
        }
        // insert item
        $this->_post_item_website(true);

        $this->open( osc_search_url() );
        // visit fisrt item
        $this->click('link=foo title');
        $this->waitForPageToLoad("10000");

        $exist_span_price = $this->isElementPresent("xpath=//span[@class='price']") ;

        if($bool == 1) { //muestra precio
            $this->assertTrue($exist_span_price , "Not exist span price!. ERROR");
        } else {
            $this->assertTrue( !$exist_span_price , "Exist span price!. ERROR");
        }
        $user = User::newInstance()->findByEmail($this->_email);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    private function _checkWebsite_enableField_images_items($bool, $num=0)
    {
        // crear user
        $this->_addUserForTesting();
        // logear con user
        $this->_loginWebsite();
        // entrar en la pag de post_item
        $this->open( osc_item_post_url() );
        $this->assertTrue($this->isTextPresent("Click or Drop for upload images"), "Not exist input photos[]. ERROR");
        $user = User::newInstance()->findByEmail($this->_email);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }


}
