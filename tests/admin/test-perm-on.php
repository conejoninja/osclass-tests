<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminPermOn extends OsclassTestAdmin
{

    function testPermOn()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_permalinks']");
        $this->waitForPageToLoad("30000");
        $value = $this->getValue('rewrite_enabled');

        if($value=='off') {
            $this->click("rewrite_enabled");
            $this->click("//input[@type='submit']");
            $this->waitForPageToLoad("30000");
            $this->assertTrue( $this->isTextPresent("Permalinks structure updated") , "Disable permalinks" );
        }

        osc_reset_preferences();
    }

}
