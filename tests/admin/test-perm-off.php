<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminPermOff extends OsclassTestAdmin
{

    function testPermOff()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_permalinks']");
        $this->waitForPageToLoad("30000");
        $value = $this->getValue('rewrite_enabled');

        if($value=='on') {
            $this->click("rewrite_enabled");
            $this->click("//input[@type='submit']");
            $this->waitForPageToLoad("30000");
            $this->assertTrue( $this->isTextPresent("Friendly URLs successfully deactivated") , "Disable permalinks" );
        }

        osc_reset_preferences();
    }

}
