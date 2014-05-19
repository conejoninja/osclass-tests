<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminEmail extends OsclassTestAdmin
{

    function testEditEmailAlert()
    {
        $this->_login();

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_emails_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("link=Edit"); // edit first email/alert
        $this->waitForPageToLoad("10000");
        $title = $this->getValue("en_US#s_title");
        $title .= " UPDATED";
        $this->type("en_US#s_title",$title);
        //$this->selectFrame("index=0");
        $body = $this->getText("//html/body");
        $this->type("xpath=//html/body[@id='tinymce']", "NEW MAIL TEXT".$body);
        //$this->selectFrame("relative=top");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue($this->isTextPresent("The email/alert has been updated"), "Edit emails and alerts");
    }

}
