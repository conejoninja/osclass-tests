<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminUser extends OsclassTestAdmin
{

    function testUserInsert()
    {
        $this->_login();
        $this->_insertUser();
        $this->_deleteUser();
        $this->_logout();
    }

    public function testUserEdit()
    {
        $this->_login();
        $this->_insertUser();
        $this->_editUser();
        $this->_deleteUser();
        $this->_logout();
    }

    public function testExtraValidations()
    {
        $this->_login();
        $this->_insertUser();
        $this->_logout();
        $this->_extraValidations();
        $this->_login();
        $this->_deleteUser();
        $this->_logout();
    }

    public function testSettings()
    {
        $this->_login();
        $this->_settings();
        $this->_logout();
    }

    public function testBulkActions()
    {
        $this->_login();

        $pref = array();
        $pref['enabled_users'] = Preference::newInstance()->findValueByName('enabled_users');
        if($pref['enabled_users'] == 1){ $pref['enabled_users'] = 'on';} else { $pref['enabled_users'] = 'off'; }
        $pref['enabled_user_validation'] = Preference::newInstance()->findValueByName('enabled_user_validation');
        if($pref['enabled_user_validation'] == 1){ $pref['enabled_user_validation'] = 'on';} else { $pref['enabled_user_validation'] = 'off'; }

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='users_settings']");
        $this->waitForPageToLoad("10000");

        // PREPARE SETTINGS
        if($pref['enabled_users']=='off' || $pref['enabled_user_validation']=='off') {
            if($pref['enabled_users']=='off') {
                $this->click("enabled_users");
            }
            if($pref['enabled_user_validation']=='off') {
                $this->click("enabled_user_validation");
            }

            $this->click("//input[@type='submit']");
            $this->waitForPageToLoad("10000");

            $this->assertTrue( $this->isTextPresent("Users' settings have been updated") , "Can't update user settings. ERROR");

            if( $pref['enabled_users'] == 'on' ){
                $this->assertTrue( $this->getValue("//input[@name='enabled_users']")=='off' );
            } else {
                $this->assertTrue( $this->getValue("//input[@name='enabled_users']")=='on' );
            }
            if( $pref['enabled_user_validation'] == 'on' ){
                $this->assertTrue( $this->getValue("//input[@name='enabled_user_validation']")=='off' );
            } else {
                $this->assertTrue( $this->getValue("//input[@name='enabled_user_validation']")=='on' );
            }

        }


        $this->_insertUser();

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='users_manage']");
        $this->waitForPageToLoad("10000");

        $this->click("//input[@id='check_all']");
        $this->select("action", "label=Deactivate");
        $this->click("//input[@id='bulk_apply']");
        $this->click("//a[@id='bulk-actions-submit']");

        $this->waitForPageToLoad("10000");
        $this->assertTrue( $this->isTextPresent("One user has been deactivated") , "Deactivate user bulk action");

        $this->click("//a[@id='users_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("//input[@id='check_all']");
        $this->select("action", "label=Resend activation");
        $this->click("//input[@id='bulk_apply']");
        $this->click("//a[@id='bulk-actions-submit']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue( $this->isTextPresent("Activation email sent to one user") , "Resend ACT user bulk action");

        $this->click("//a[@id='users_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("//input[@id='check_all']");
        $this->select("action", "label=Activate");
        $this->click("//input[@id='bulk_apply']");
        $this->click("//a[@id='bulk-actions-submit']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue( $this->isTextPresent("One user has been activated") , "Activate user bulk action");

        $this->click("//a[@id='users_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("//input[@id='check_all']");
        $this->select("action", "label=Block");
        $this->click("//input[@id='bulk_apply']");
        $this->click("//a[@id='bulk-actions-submit']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue( $this->isTextPresent("One user has been blocked") , "Block user bulk action");

        $this->click("//a[@id='users_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("//input[@id='check_all']");
        $this->select("action", "label=Unblock");
        $this->click("//input[@id='bulk_apply']");
        $this->click("//a[@id='bulk-actions-submit']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue( $this->isTextPresent("One user has been unblocked") , "Unblock user bulk action");

        $this->click("//a[@id='users_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("//input[@id='check_all']");
        $this->select("action", "label=Delete");
        $this->click("//input[@id='bulk_apply']");
        $this->click("//a[@id='bulk-actions-submit']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue( $this->isTextPresent("One user has been deleted") , "Delete user bulk action");



        // RESET CHANGES
        if($pref['enabled_users']=='off' || $pref['enabled_user_validation']=='off') {
            $this->open( osc_admin_base_url(true) );
            $this->click("//a[@id='users_settings']");
            $this->waitForPageToLoad("10000");

            if($pref['enabled_users']=='off') {
                $this->click("enabled_users");
            }
            if($pref['enabled_user_validation']=='off') {
                $this->click("enabled_user_validation");
            }

            $this->click("//input[@type='submit']");
            $this->waitForPageToLoad("10000");

            $this->assertTrue( $this->isTextPresent("Users' settings have been updated") , "Can't update user settings. ERROR");

            if( $pref['enabled_users'] == 'on' ){
                $this->assertTrue( $this->getValue("//input[@name='enabled_users']")=='off' );
            } else {
                $this->assertTrue( $this->getValue("//input[@name='enabled_users']")=='on' );
            }
            if( $pref['enabled_user_validation'] == 'on' ){
                $this->assertTrue( $this->getValue("//input[@name='enabled_user_validation']")=='off' );
            } else {
                $this->assertTrue( $this->getValue("//input[@name='enabled_user_validation']")=='on' );
            }
        }
    }


    public function testBanSystem()
    {
        $this->_login();
        // CREATE RULE
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='users_ban']");
        $this->waitForPageToLoad("10000");
        $this->click("link=Add new");
        $this->waitForPageToLoad("10000");

        $this->type("s_name", "Ban rule #1");
        $this->type("s_email", "*t@osclass.org");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Rule saved correctly") , "Can't add ban rule. ERROR");

        // TEST RULE

        $this->open( osc_base_url() );
        $this->click("link=Register for a free account");
        $this->waitForPageToLoad("30000");

        $this->type('s_name'      , 'testuser');
        $this->type('s_password'  , 'asdfasdf');
        $this->type('s_password2' , 'asdfasdf');
        $this->type('s_email'     , 'test@osclass.org');

        $this->click("//button[text()='Create']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue( $this->isTextPresent("Your current email is not allowed") , "Can register email so to ban rule failed. ERROR");

        $this->type('s_name'      , 'testuser');
        $this->type('s_password'  , 'asdfasdf');
        $this->type('s_password2' , 'asdfasdf');
        $this->type('s_email'     , 'testok@osclass.org');

        $this->click("//button[text()='Create']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue( ($this->isTextPresent("Your account has been created successfully") || $this->isTextPresent("The user has been created")), "Can't register email due to ban rule. ERROR");

        $this->_deleteUser('testok@osclass.org');


        // TEST WAYS TO REMOVE RULES
        //$this->_login();

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='users_ban']");
        $this->waitForPageToLoad("10000");
        $this->click("//input[@id='check_all']");
        $this->select("//select[@name='action']", "label=Delete");
        $this->click("//input[@id='bulk_apply']");
        sleep(2);
        $this->click("//a[@id='bulk-actions-submit']");
        $this->waitForPageToLoad("30000");
        // "regexpi:This is SeleniumWiki.com"
        $this->assertTrue($this->isTextPresent( "regexpi:rules have been deleted") || $this->isTextPresent( "regexpi:rule has been deleted"));

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='users_ban']");
        $this->waitForPageToLoad("10000");
        $this->click("link=Add new");
        $this->waitForPageToLoad("10000");

        $this->type("s_name", "Ban rule #1");
        $this->type("s_email", "*t@osclass.org");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Rule saved correctly") , "Can't add ban rule. ERROR");

        //$this->mouseOver("//td[contains(.,'Ban rule #1')]");
        $this->click("//td[contains(.,'Ban rule #1')]/div[@class='actions']/ul/li/a[text()='Delete']");
        sleep(2);
        $this->click("//input[@id='ban-delete-submit']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue($this->isTextPresent('One ban rule has been deleted'));

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='users_ban']");
        $this->waitForPageToLoad("10000");
        $this->click("link=Add new");
        $this->waitForPageToLoad("10000");

        $this->type("s_name", "Ban rule #1");
        $this->type("s_email", "*t@osclass.org");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Rule saved correctly") , "Can't add ban rule. ERROR");

        $this->click("//table/tbody/tr[contains(.,'Ban rule #1')]/td/input");

        $this->select("//select[@name='action']", "label=Delete");
        $this->click("//input[@id='bulk_apply']");
        sleep(2);
        $this->click("//a[@id='bulk-actions-submit']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue($this->isTextPresent( "One ban rule has been deleted"));
    }

    private function _insertUser()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("xpath=//a[@id='users_manage']");
        $this->waitForPageToLoad("30000");
        $this->click("link=Add new");
        $this->waitForPageToLoad("30000");

        $this->type("s_email"         ,"");
        $this->type("s_password"      ,"");
        $this->type("s_password2"     ,"");
        $this->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue($this->isTextPresent("Email: this field is required"),"Add user. JS validation");
        $this->assertTrue($this->isTextPresent("Password: this field is required"),"Add user. JS validation");
        $this->assertTrue($this->isTextPresent("Second password: this field is required"),"Add user. JS validation");

        $this->type("s_password"      ,"bsg");
        $this->type("s_password2"     ,"pegasus");
        $this->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue($this->isTextPresent("Password: enter at least 5 characters"), "Add user. JS validation");
        $this->assertTrue($this->isTextPresent("Passwords don't match"), "Add user. JS validation");

        $this->type("s_email"         ,"test@mail.com");
        $this->type("s_password"      ,"password");
        $this->type("s_password2"     ,"password");

        $this->type("s_name"          ,"real name user");

        $this->type("s_phone_mobile"  ,"666666666");
        $this->type("s_phone_land"    ,"930112233");

        $this->type("s_website"       ,"http://osclass.org");
        $this->type("s_info[en_US]"   ,"foobar description");

        $this->type("cityArea"        ,"city area");
        $this->type("address"         ,"address user");

        //$this->select("countryId", "label=Spain");
        $this->type("country"      , "Spain");
        $this->select("regionId", "label=Barcelona");
        sleep(1);
        $this->select("cityId", "label=Barcelona");
        //$this->type("region"      , "Barcelona");
        //$this->type("city"        , "Barcelona");
        $this->select("b_company"     , "label=User");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
        sleep(1);
        $this->assertTrue($this->isTextPresent("The user has been created successfully"),"Create user");
    }

    private function _deleteUser($email = 'mail.com')
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='users_manage']");
        $this->waitForPageToLoad("30000");

        $this->click("xpath=//table/tbody/tr[contains(.,'$email')]/td/div/ul/li/a[text()='Delete']");
        $this->click("//input[@id='user-delete-submit']");

        $this->waitForPageToLoad("30000");
        sleep(1);

        $this->assertTrue($this->isTextPresent("One user has been deleted"), "Delete user" );
    }


    private function _editUser()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("xpath=//a[@id='users_manage']");
        $this->waitForPageToLoad("10000");

        $this->mouseOver("xpath=//table/tbody/tr[contains(.,'mail.com')]");
        $this->click("xpath=//table/tbody/tr[contains(.,'mail.com')]/td/div/ul/li/a[text()='Edit']");
        $this->waitForPageToLoad("10000");

        $this->type("s_email"         ,"");
        $this->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue($this->isTextPresent("Email: this field is required"),"Edit user. JS validation");

        $this->type("s_password", "bsg");
        $this->type("s_password2", "bsg");
        $this->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue($this->isTextPresent("Password: enter at least 5 characters"),"Edit user. JS validation");
        $this->assertTrue($this->isTextPresent("Second password: enter at least 5 characters"),"Edit user. JS validation");

        $this->type("s_password", "galactica");
        $this->type("s_password2", "pegasus");
        $this->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue($this->isTextPresent("Passwords don't match"),"Edit user. JS validation");


        $this->type("s_email"         ,"newtest@mail.com");
        $this->type("s_password"      ,"newpassword");
        $this->type("s_password2"     ,"newpassword");

        $this->type("s_name"          ,"new real name user");

        $this->type("s_phone_mobile"  ,"999999999");
        $this->type("s_phone_land"    ,"332211039");

        $this->type("s_website"       ,"http://osclass.org");
        $this->type("s_info[en_US]"   ,"new foobar description");

        $this->type("cityArea"        ,"new city area");
        $this->type("address"         ,"new address user");

        //$this->select("countryId"     , "label=Spain");
        $this->type("country"       , "Spain");
        $this->select("regionId"      , "label=Madrid");
        sleep(1);
        $this->select("cityId"        , "label=La Acebeda");
        $this->select("b_company"     , "label=Company");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("The user has been updated"),"Edit user");
    }

    private function _extraValidations()
    {
        // add item no user logged

        osc_set_preference('reg_user_post', 0);
        osc_set_preference('moderate_items', -1);

        // add item for testing purposes
        $this->open(osc_base_url(true) . '?page=item&action=item_add' );
        $this->select("catId", "label=regexp:\\s*Animals");
        //$this->select("select_1", "label=regexp:\\s*For sale");
        //$this->select("select_2", "label=regexp:\\s*Animals");

        $this->type("title[en_US]", 'Title new add test');
        $this->type("description[en_US]", "description new add");
        $this->type("price", '11');

        //$this->select("countryId", "label=Spain");
        $this->select("countryId", "label=Spain");
        //$this->select("regionId", "label=Barcelona");
        //$this->select("cityId", "label=Barcelona");
        sleep(1);
        $this->type("region", "Barcelona");
        sleep(1);
        $this->type("city", "Barcelona");

        $this->type('id=contactName', 'foobar');
        $this->type('id=contactEmail', 'foobar@mail.com');

        $this->select("currency", "label=Euro €");
        $this->click("//button[text()='Publish']");
        $this->waitForPageToLoad("30000");


        // log in website
        $this->open( osc_base_url(true) );
        $this->click("login_open");
        $this->waitForPageToLoad("30000");
        $this->type("email"   , 'test@mail.com');
        $this->type("password", 'password');

        $this->click("//button[@type='submit']");
        $this->waitForPageToLoad("30000");

        // check username at left up corner
        $this->assertTrue($this->isTextPresent('real name user'),"Login at website");
        // check autofill locations when user add nen advert
        $this->open(osc_base_url(true) . '?page=item&action=item_add');
        $this->waitForPageToLoad("30000");
        //sleep(30);
        // TODO ENABLE THIS WHEN FIXED
        //$this->assertTrue( ($this->getSelectedLabel('id=countryId') == 'Spain'), 'Country auto fill');
        //$this->assertTrue( ($this->getValue('id=country') == 'Spain'), 'Country auto fill');
        $this->assertTrue( ($this->getValue('id=region')  == 'Barcelona'), 'Region auto fill');
        $this->assertTrue( ($this->getValue('id=city')  == 'Barcelona'), 'City auto fill');
        $this->assertTrue( ($this->getValue('id=cityArea') == 'city area'), 'City area auto fill');
        $this->assertTrue( ($this->getValue('id=address') == 'address user'), 'Address auto fill');
        sleep(3);
        // alerts
        $this->open(osc_base_url(true) . '?page=search');
        $this->assertTrue( ($this->getValue('id=alert_email') == 'test@mail.com' ), 'Email inserted for alert');
        // contact publisher (need add one item)
        $this->open(osc_base_url(true) . '?page=search');
        $this->click('link=Title new add test');
        $this->waitForPageToLoad("30000");

        $this->assertTrue( ($this->getValue('id=yourName') == 'real name user'), 'Name auto fill');
        $this->assertTrue( ($this->getValue('id=yourEmail') == 'test@mail.com'), 'Email auto fill');

        // remove item
        $aItems = Item::newInstance()->findByEmail( 'foobar@mail.com' );
        foreach($aItems as $item) {
            Item::newInstance()->deleteByPrimaryKey($item['pk_i_id']);
        }
    }

    private function _settings()
    {
        
        $pref = array();
        $pref['enabled_users'] = Preference::newInstance()->findValueByName('enabled_users');
        if($pref['enabled_users'] == 1){ $pref['enabled_users'] = 'on';} else { $pref['enabled_users'] = 'off'; }
        $pref['enabled_user_validation'] = Preference::newInstance()->findValueByName('enabled_user_validation');
        if($pref['enabled_user_validation'] == 1){ $pref['enabled_user_validation'] = 'on';} else { $pref['enabled_user_validation'] = 'off'; }
        $pref['enabled_user_registration'] = Preference::newInstance()->findValueByName('enabled_user_registration');
        if($pref['enabled_user_registration'] == 1){ $pref['enabled_user_registration'] = 'on';} else { $pref['enabled_user_registration'] = 'off'; }

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='users_settings']");
        $this->waitForPageToLoad("10000");

        $this->click("enabled_users");
        $this->click("enabled_user_validation");
        $this->click("enabled_user_registration");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("User settings have been updated") , "Can't update user settings. ERROR");

        if( $pref['enabled_users'] == 'on' ){
            $this->assertTrue( $this->getValue("//input[@name='enabled_users']")=='off' );
        } else {
            $this->assertTrue( $this->getValue("//input[@name='enabled_users']")=='on' );
        }
        if( $pref['enabled_user_validation'] == 'on' ){
            $this->assertTrue( $this->getValue("//input[@name='enabled_user_validation']")=='off' );
        } else {
            $this->assertTrue( $this->getValue("//input[@name='enabled_user_validation']")=='on' );
        }
        if( $pref['enabled_user_registration'] == 'on' ){
            $this->assertTrue( $this->getValue("//input[@name='enabled_user_registration']")=='off' );
        } else {
            $this->assertTrue( $this->getValue("//input[@name='enabled_user_registration']")=='on' );
        }

        $this->click("enabled_users");
        $this->click("enabled_user_validation");
        $this->click("enabled_user_registration");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->getValue("//input[@name='enabled_users']")              ==  $pref['enabled_users'] );
        $this->assertTrue( $this->getValue("//input[@name='enabled_user_validation']")    ==  $pref['enabled_user_validation'] );
        $this->assertTrue( $this->getValue("//input[@name='enabled_user_registration']")  ==  $pref['enabled_user_registration'] );

        $this->assertTrue( $this->isTextPresent("User settings have been updated") , "Can't update user settings. ERROR");

        /*
         * Testing deeper
         */

        // enabled_users
        Preference::newInstance()->replace('enabled_users', '0',"osclass", 'INTEGER');
        $this->_checkWebsite_enabled_users(0);
        Preference::newInstance()->replace('enabled_users', '1',"osclass", 'INTEGER');
        $this->_checkWebsite_enabled_users(1);
        // enabled_user_validation
        Preference::newInstance()->replace('enabled_user_validation', '0',"osclass", 'INTEGER');
        $this->_checkWebsite_enabled_user_validation(0);
        Preference::newInstance()->replace('enabled_user_validation', '1',"osclass", 'INTEGER');
        $this->_checkWebsite_enabled_user_validation(1);
        // enabled_user_registration
        Preference::newInstance()->replace('enabled_user_registration', '0',"osclass", 'INTEGER');
        $this->_checkWebsite_enabled_user_registration(0);
        Preference::newInstance()->replace('enabled_user_registration', '1',"osclass", 'INTEGER');
        $this->_checkWebsite_enabled_user_registration(1);
        osc_reset_preferences();
    }

    private function _checkWebsite_enabled_users($bool)
    {
        $this->open( osc_user_login_url() );
        if($bool == 1) {
            $is_present_email = $this->isElementPresent('id=email');
            $is_present_pass  = $this->isElementPresent('id=password');
            $this->assertTrue(( $is_present_email && $is_present_pass), "Login" );
        } else if ($bool == 0) {
            $this->assertTrue($this->isTextPresent('Users not enabled'), "Login" );
        }

        $this->open( osc_register_account_url() );
        if($bool == 1) {
            $is_present_email = $this->isElementPresent('id=s_name');
            $is_present_pass  = $this->isElementPresent('id=s_password');
            $is_present_pass2 = $this->isElementPresent('id=s_password2');
            $this->assertTrue(( $is_present_email && $is_present_pass && $is_present_pass2 ), "Register" );
        } else if ($bool == 0) {
            $this->assertTrue($this->isTextPresent('Users not enabled'), "Register" );
        }
    }

    private function _checkWebsite_enabled_user_validation($bool)
    {
        $this->open( osc_register_account_url() );
        $this->type('id=s_name', "William Adama");
        $this->type('id=s_password', "galactica");
        $this->type('id=s_password2', "galactica");
        $this->type('id=s_email', "testing+testb@osclass.org");
        $this->click("xpath=//button[text()='Create']");
        $this->waitForPageToLoad("10000");

        if($bool == 1) {
            $this->assertTrue( $this->isTextPresent('The user has been created. An activation email has been sent'), "No-Validate user" );
        } else if ($bool == 0) {
            $this->assertTrue($this->isTextPresent('Your account has been created successfully'), "Validate user" );
        }

        $user = User::newInstance()->findByEmail("testing+testb@osclass.org");
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    private function _checkWebsite_enabled_user_registration($bool)
    {
        $this->open( osc_register_account_url() );
        if($bool == 1) {
            $is_present_email = $this->isElementPresent('id=s_name');
            $is_present_pass  = $this->isElementPresent('id=s_password');
            $is_present_pass2 = $this->isElementPresent('id=s_password2');
            $this->assertTrue(( $is_present_email && $is_present_pass && $is_present_pass2 ), "Register user" );
        } else if ($bool == 0) {
            $this->assertTrue($this->isTextPresent('User registration is not enabled'), "Register user" );
        }
    }



}
