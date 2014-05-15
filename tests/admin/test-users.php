<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminUser extends OsclassTestAdmin
{

    function _testUserInsert()
    {
        $this->_login() ;
        $this->_insertUser();
        $this->_deleteUser();
    }

    public function _testUserEdit()
    {
        $this->_login() ;
        $this->_insertUser() ;
        $this->_editUser();
        $this->_deleteUser();
    }

    public function _testExtraValidations()
    {
        $this->_login() ;
        $this->_insertUser() ;
        $this->_extraValidations();
        $this->_deleteUser();
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

        $this->assertTrue($this->isTextPresent("One user has been deleted"), "Delete user" ) ;
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
        $this->assertTrue( ($this->getSelectedLabel('id=countryId') == 'Spain'), 'Country auto fill');
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
        $aItems = Item::newInstance()->findByEmail( 'foobar@mail.com' ) ;
        foreach($aItems as $item) {
            Item::newInstance()->deleteByPrimaryKey($item['pk_i_id']);
        }
    }


}
