<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminStats extends OsclassTestAdmin
{

    function testStats()
    {
        $this->_login() ;
        $this->open( osc_admin_base_url(true) );
        $this->waitForPageToLoad("10000");

        $this->click("//a[@id='stats_users']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("User Statistics"),"User Statistics");

        $this->click("//a[@id='stats_items']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Listing Statistics"),"Listing Statistics");

        $this->click("//a[@id='stats_comments']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Comment Statistics"),"Comments Statistics");

        $this->click("//a[@id='stats_reports']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Report Statistics"),"Reports Statistics");
    }

}
