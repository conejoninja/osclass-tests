<?php

require_once dirname(__FILE__) . '/OsclassTest.php';
require_once TEST_SERVER_PATH . '/oc-load.php';

class OsclassTestFrontend extends OsclassTest
{

    private $_mUser;
    public function setUp() {
        parent::setUp();
        $this->_mUser = User::newInstance();
    }

    protected function _userRegistration($email = TEST_USER_EMAIL, $pass = TEST_USER_PASS, $name = 'Test')
    {
        osc_set_preference('enabled_users', true);
        osc_set_preference('enabled_user_registration', true);
        osc_set_preference('enabled_user_validation', false);

        $this->open(osc_base_url());
        $this->click("link=Register for a free account");
        $this->waitForPageToLoad("30000");
        $this->type("id=s_name", $name);
        $this->type("id=s_email", $email);
        $this->type("id=s_password", $pass);
        $this->type("id=s_password2", $pass);
        $this->click("//button[@type='submit']");
        $this->waitForPageToLoad("30000");

        $user = User::newInstance()->findByEmail($email);
        return @$user['pk_i_id'];
    }

    protected function _login($email = TEST_USER_EMAIL, $pass = TEST_USER_PASS)
    {
        $this->open(osc_user_login_url());
        $this->click("id=login_open");
        $this->waitForPageToLoad("30000");
        $this->type("id=email", $email);
        $this->type("id=password", $pass);
        $this->click("//button[@type='submit']");
        $this->waitForPageToLoad("30000");
    }

    protected function _logout()
    {
        $this->open(osc_user_login_url());
        $this->click("link=Logout");
        $this->waitForPageToLoad("30000");
    }

    protected function _removeUserByEmail($email)
    {
        $user = $this->_mUser->findByEmail($email);
        $this->_mUser->deleteUser($user['pk_i_id']);
    }

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


    function _createAlert($email, $success = true)
    {
        $this->open(osc_search_url());
        $this->waitForPageToLoad("10000");

        $this->type('alert_email', $email);
        $this->click("xpath=//form[@id='sub_alert']/button");
        sleep(3);
        //if($success) {
            $this->getAlert();
            //$this->chooseOkOnNextAlert();
        //}

        $aAuxAlert = Alerts::newInstance()->findByEmail($email);
        if( $success ) {
            $this->assertTrue(count($aAuxAlert) == 1, 'Search - create alert');
        } else {
            $this->assertTrue(count($aAuxAlert) == 0, 'Search - create alert');
        }
    }
    

}
