<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminLanguage extends OsclassTestAdmin
{

    private $_canUpload = true;

    function testPreUpload()
    {
        @chmod(CONTENT_PATH."uploads/", 0777);
        @chmod(CONTENT_PATH."languages/", 0777);
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_language']");
        $this->waitForPageToLoad("10000");
        $this->click("link=Add new");
        $this->waitForPageToLoad("10000");

        $this->assertFalse($this->isTextPresent('To make the directory writable'));
        if( $this->isTextPresent('To make the directory writable') ) {
            $this->assertFalse(true,"DIRECTORY TO UPLOAD LANGUAGES ISN'T WRITABLE") ;
            $this->_canUpload = false;
        }else{
            $this->_canUpload = true;
        }
    }

    function testInsertLanguage()
    {
        if($this->_canUpload){
            $this->_login();
            // insert language
            //$this->_deleteLanguage("Spanish", false);
            $this->open( osc_admin_base_url(true) ) ;
            $this->click("//a[@id='settings_language']");
            $this->waitForPageToLoad("10000");
            $this->click("link=Add new");
            $this->waitForPageToLoad("10000");
            $this->type("package", TEST_ASSETS_PATH . 'lang_es_ES.zip');
            $this->click("//input[@type='submit']");
            $this->waitForPageToLoad("10000");
            $this->assertTrue($this->isTextPresent("The language has been installed correctly"),"Upload new language lang_es_ES_2.0.zip");
            $this->_logout();
        }
    }

    function testInsertWrongLanguage()
    {
        if($this->_canUpload){
            $this->_login();
            // insert language
            $this->_deleteLanguage("Spanish", false);
            $this->open( osc_admin_base_url(true) ) ;
            $this->click("//a[@id='settings_language']");
            $this->waitForPageToLoad("10000");
            $this->click("link=Add new");
            $this->waitForPageToLoad("10000");
            $this->type("package", TEST_ASSETS_PATH . 'img_test1.png');
            $this->click("//input[@type='submit']");
            $this->waitForPageToLoad("10000");
            $this->assertTrue($this->isTextPresent("The zip file is not valid"),"Upload WRONG language file");
            $this->_logout();
            $this->testInsertLanguage();
        }
    }

    function testEnableDisable()
    {
        if($this->_canUpload){
            $this->_login();
            if( $this->_isDisabledWebsite("Spanish") ){
                $this->_enableWebsite("Spanish");
                $this->_checkWebsiteEnabled("Spanish");
                $this->_logout();
                $this->_login();
                $this->_disableWebsite("Spanish");
                $this->_logout();
                $this->_login();
                $this->_checkWebsiteDisabled("Spanish");
                $this->_logout();
                $this->_login();
            } else {
                $this->_disableWebsite("Spanish");
                $this->_logout();
                $this->_login();
                $this->_checkWebsiteDisabled("Spanish");
                $this->_logout();
                $this->_login();
                $this->_enableWebsite("Spanish");
                $this->_logout();
                $this->_login();
                $this->_checkWebsiteEnabled("Spanish");
                $this->_logout();
                $this->_login();
            }


            if( $this->_isDisabledOCAdmin("Spanish") ) {
                $this->_enableOCAdmin("Spanish");
                $this->_logout();
                $this->_checkOCAdminEnabled("Spanish");

                $this->_login();
                $this->_disableOCAdmin("Spanish");
                $this->_logout();
                $this->_checkOCAdminDisabled("Spanish");
            } else {
                $this->_disableOCAdmin("Spanish");
                $this->_logout();
                $this->_checkOCAdminDisabled("Spanish");
                $this->_login();
                $this->_enableOCAdmin("Spanish");
                $this->_logout();
                $this->_checkOCAdminEnabled("Spanish");
            }
        }
    }

    public function testLanguageEdit()
    {
        if($this->_canUpload){
            $this->_login();
            $this->open( osc_admin_base_url(true) );
            $this->waitForPageToLoad("10000");
            $this->click("//a[@id='settings_language']");
            $this->waitForPageToLoad("10000");
            $this->mouseOver("xpath=//table/tbody/tr[contains(.,'Spanish')]");
            $this->click("xpath=//table/tbody/tr[contains(.,'Spanish')]/td/div/ul/li/a[contains(.,'Edit')]");
            $this->waitForPageToLoad("10000");

            // TEST JS VALIDATION
            $this->type("s_name","");
            $this->type("s_short_name","");
            $this->type("s_description","");
            $this->type("s_currency_format","");
            $this->type("i_num_dec","sfd");
            $this->type("s_dec_point","");
            $this->type("s_thousands_sep","");
            $this->type("s_date_format","");
            $this->click("xpath=//input[@type='submit']");

            sleep(4);

            $this->assertTrue($this->isTextPresent("Number of decimals: this field must only contain numeric characters."),"Edit language Spanish - JS validation -");
            $this->assertTrue($this->isTextPresent("Name: this field is required."),"Edit language Spanish - JS validation -");
            $this->assertTrue($this->isTextPresent("Short name: this field is required."),"Edit language Spanish - JS validation -");
            $this->assertTrue($this->isTextPresent("Description: this field is required."),"Edit language Spanish - JS validation -");
            $this->assertTrue($this->isTextPresent("Currency format: this field is required."),"Edit language Spanish - JS validation -");
            $this->assertTrue($this->isTextPresent("Decimal point: this field is required."),"Edit language Spanish - JS validation -");
            $this->assertTrue($this->isTextPresent("Date format: this field is required."),"Edit language Spanish - JS validation -");

            $this->click("//a[@id='settings_language']");
            $this->waitForPageToLoad("10000");
            $this->mouseOver("xpath=//table/tbody/tr[contains(.,'Spanish')]");
            $this->click("xpath=//table/tbody/tr[contains(.,'Spanish')]/td/div/ul/li/a[contains(.,'Edit')]");
            $this->waitForPageToLoad("10000");
            $this->type("s_name","Spanish upadated");
            $this->type("s_short_name","Spanish upadated");
            $this->type("s_description","Spanish translation updated");
            $this->type("s_currency_format","currency");
            $this->type("i_num_dec","3");
            $this->type("s_dec_point","x");
            $this->type("s_thousands_sep","y");
            $this->type("s_date_format","Ymd");
            $this->type("s_stop_words","foo,bar");
            $this->click("b_enabled");
            $this->click("b_enabled_bo");
            $this->click("//input[@type='submit']");
            $this->waitForPageToLoad("10000");
            $this->assertTrue($this->isTextPresent("Spanish upadated has been updated"),"Edit language Spanish");
        }
    }

    public function testDeleteLanguage()
    {
        if($this->_canUpload){
            $this->_login();
            $this->_deleteLanguage();
            $this->_logout();
            // Re-insert language (needed by installation test)
            sleep(4);
            $this->testInsertLanguage();
        }
    }

    private function _deleteLanguage($lang = "Spanish", $check = true)
    {
        $this->_doAction("Delete");
        $this->waitForPageToLoad("10000");
        if($check) {
            $this->assertTrue($this->isTextPresent("has been successfully removed"),"Delete language Spanish");
        }
    }

    private function _doAction($action, $lang = "Spanish")
    {
        $this->open( osc_admin_base_url(true) );
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='settings_language']");
        $this->waitForPageToLoad("10000");

        //$this->mouseOver("xpath=//table/tbody/tr/tr[contains(.,'$lang')]");
        $this->click("xpath=//table/tbody/tr[contains(.,'$lang')]/td/div/ul/li/a[text()='$action']");
        if($action == 'Delete') {
            $this->click("xpath=//input[@id='language-delete-submit']");
        }
        $this->waitForPageToLoad("10000");
    }

    private function _isDisabledOCAdmin($lang)
    {
        $this->open( osc_admin_base_url(true) ) ;
        $this->click("//a[@id='settings_language']");
        $this->waitForPageToLoad("10000");

        $text = $this->getText("//table/tbody/tr/td[contains(.,'$lang')]/div/ul/li/a[text()='Disable (oc-admin)']");
        $bool = preg_match('/Disable \(oc-admin\)/i', $text);
        if($bool) {
            //echo "====> ".$text."   </br>";
            return false;
        } else {
            return true;
        }
    }

    private function _isDisabledWebsite($lang)
    {
        $this->open( osc_admin_base_url(true) ) ;
        $this->click("//a[@id='settings_language']");
        $this->waitForPageToLoad("10000");

        $text = $this->getText("//table/tbody/tr/td[contains(.,'$lang')]/div/ul/li/a[text()='Enable (website)']");
        $bool = preg_match('/Enable \(website\)/i', $text);
        if($bool) {
            return true;
        } else {
            return false;
        }
    }

    private function _enableWebsite($lang)
    {
        $this->_doAction("Enable (website)", $lang);
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Selected languages have been enabled for the website"),"Enable (website) language $lang");
    }

    private function _checkWebsiteEnabled($lang)
    {
        $this->open( osc_base_url(true) ) ;
        // position cursor on language
        //$this->mouseMove("xpath=//strong[text()='Language']");
        $this->assertTrue($this->isTextPresent("$lang"),"The language has not been activated correctly (website language $lang)");

        //$this->click("link=$lang");
        $this->click("//a[contains(.,'$lang')]");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("Publica tu anuncio gratis"),"Find $lang strings (website language $lang)");
    }

    private function _disableWebsite($lang)
    {
        $this->_doAction("Disable (website)", $lang);
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Selected languages have been disabled for the website"),"Disable (website) language $lang");
    }

    private function _checkWebsiteDisabled($lang)
    {
        $this->assertTrue($this->isTextPresent("Language"),"There are more than en_US language at website");
    }

    private function _enableOCAdmin($lang)
    {
        $this->_doAction("Enable (oc-admin)", $lang);
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("Selected languages have been enabled for the backoffice (oc-admin)"),"Enable (backoffice) language $lang");
    }

    private function _checkOCAdminEnabled($lang)
    {
        $this->open( osc_admin_base_url(true) );
        $language = $this->isTextPresent('Language') ;
        if( $language ){
            $this->select('id=user_language', "$lang") ;
            $this->type('user', 'testadmin');
            $this->type('password', 'password');
            sleep(10);
            $this->click('submit');
            $this->waitForPageToLoad(1000);

            //if( $this->isTextPresent('Desconectar') ) {
            //    $this->click('Desconectar');
            if( $this->isTextPresent('Sign out') ) {
                $this->click('Sign out');
                $this->assertTrue(TRUE);
            } else {
                $this->click('Sign out');
                $this->assertTrue(FALSE, "The language has not been activated correctly OCAdmin $lang");
            }
            $this->waitForPageToLoad(1000);
        } else {
            $this->assertTrue(TRUE,'There aren\'t selector of language at OCAdmin' );
        }
    }

    private function _disableOCAdmin($lang)
    {
        $this->_doAction("Disable (oc-admin)", $lang);
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Selected languages have been disabled for the backoffice (oc-admin)"),"Disable (backoffice) language $lang");
    }

    private function _checkOCAdminDisabled($lang)
    {
        $this->open( osc_admin_base_url(true) );
        $this->assertTrue(!$this->isTextPresent('Language'), "There are more than en_US language at OCAdmin");
    }


}
