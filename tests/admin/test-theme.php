<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminTheme extends OsclassTestAdmin
{

    function _testAddTheme()
    {
        $this->_login();

        @chmod(CONTENT_PATH."themes/", 0777);
        $this->open(osc_admin_base_url(true));
        $this->click("link=Appearance");
        $this->waitForPageToLoad("10000");
        $this->click("link=Add new");
        $this->waitForPageToLoad("10000");

        if ($this->isTextPresent("chmod a+w ")) {
            $this->assertTrue(FALSE, "NOTICE, You need give permissions to the folder");
        } else {
            $this->type("package", TEST_ASSETS_PATH . "newcorp.zip");
            $this->click("//input[@type='submit']");
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isTextPresent("The theme has been installed correctly"), "Add a new theme.");
        }
    }

    function _testActivateTheme()
    {
        $this->_login();

        $this->open(osc_admin_base_url(true));
        $this->click("link=Appearance");
        $this->waitForPageToLoad("10000");

        $this->click("//a[@href[contains(.,'newcorp')] and text()='Activate']");
        $this->waitForPageToLoad("10000");
        sleep(1);
        $this->assertTrue($this->isTextPresent('Theme activated correctly'), "Activate newcorp theme.");


        $this->click("link=Appearance");
        $this->waitForPageToLoad("10000");

        $this->click("//a[@href[contains(.,'bender')] and text()='Activate']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent('Theme activated correctly'), "Activate bender theme.");
    }

    // TODO TEST WIDGETS, BENDER DO *NOT* HAVE WIDGETS TO TEST IT OUT
    function testWidgets()
    {
        $this->_login();

        $this->open(osc_admin_base_url(true));
        $this->click("link=Appearance");
        $this->waitForPageToLoad("10000");

        $this->click("//a[@href[contains(.,'newcorp')] and text()='Activate']");
        $this->waitForPageToLoad("10000");
        sleep(1);
        $this->assertTrue($this->isTextPresent('Theme activated correctly'), "Activate newcorp theme.");

        $this->_widgetsHeader();
        $this->_widgetsFooter();
        $this->_editWidgetsHeader();

        $this->open(osc_admin_base_url(true));
        $this->click("link=Appearance");
        $this->waitForPageToLoad("10000");

        $this->click("//a[@href[contains(.,'bender')] and text()='Activate']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent('Theme activated correctly'), "Activate bender theme.");
        
        
    }

    private function _widgetsHeader()
    {
        $this->open(osc_admin_base_url(true));
        $this->click("link=Manage widgets");
        $this->waitForPageToLoad("10000");

        // add header widget
        $this->click("//a[@id='add_widget_header']");
        $this->waitForPageToLoad("10000");
        $this->type("description", "header1");
        $this->selectFrame("index=0");
        $this->type("xpath=//html/body[@id='tinymce']", "New Widget Header");
        $this->selectFrame("relative=top");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue($this->isTextPresent("Widget added correctly"), "Add widget header.");
        $this->assertTrue($this->isTextPresent("header1"), "Check widget header oc-admin.");

        // check if appear at frontend
        $this->open(osc_base_url(true));
        $this->assertTrue($this->isTextPresent('New Widget Header'), "Header widget at website.");

        $this->open(osc_admin_base_url(true));
        $this->click("link=Manage widgets");
        $this->waitForPageToLoad("10000");

        // remove widget
        $this->click("link=Delete");
        sleep(1);
        $this->click("xpath=//input[@id='widget-delete-submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue($this->isTextPresent("Widget removed correctly"), "Delete widget header.");
        $this->assertTrue(!$this->isTextPresent("header1"), "Check delete widget header.");
    }

    private function _widgetsFooter() {
        $this->open(osc_admin_base_url(true));
        $this->click("link=Manage widgets");
        $this->waitForPageToLoad("10000");

        // add categories widget
        $this->click("//a[@id='add_widget_footer']");
        $this->waitForPageToLoad("10000");
        $this->type("description", "footer1");
        $this->selectFrame("index=0");
        $this->type("xpath=//html/body[@id='tinymce']", "New Widget Footer");
        $this->selectFrame("relative=top");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue($this->isTextPresent("Widget added correctly"), "Ad widget footer.");
        $this->assertTrue($this->isTextPresent("footer1"), "Check add widget footer.");

        // check if appear at frontend
        $this->open(osc_base_url(true));
        $this->assertTrue($this->isTextPresent('New Widget Footer'), "Footer widget at website.");

        $this->open(osc_admin_base_url(true));
        $this->click("link=Manage widgets");
        $this->waitForPageToLoad("10000");

        //remove widget
        $this->click("link=Delete");
        $this->click("xpath=//input[@id='widget-delete-submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue($this->isTextPresent("Widget removed correctly"), "Delete widget footer.");
        $this->assertTrue(!$this->isTextPresent("footer1"), "Check delete widget footer.");
    }

    private function _editWidgetsHeader()
    {
        $this->open(osc_admin_base_url(true));
        $this->click("link=Manage widgets");
        $this->waitForPageToLoad("10000");

        // add header widget
        $this->click("//a[@id='add_widget_header']");
        $this->waitForPageToLoad("10000");
        $this->type("description", "header1");
        $this->selectFrame("index=0");
        $this->type("xpath=//html/body[@id='tinymce']", "New Widget Header");
        $this->selectFrame("relative=top");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue($this->isTextPresent("Widget added correctly"), "Add widget header.");
        $this->assertTrue($this->isTextPresent("header1"), "Check add widget header.");

        // edit html
        // add header widget
        $this->click("link=Edit");
        $this->waitForPageToLoad("10000");
        $this->selectFrame("index=0");
        $this->type("xpath=//html/body[@id='tinymce']", "New Widget Header - NEW CONTENT");
        $this->selectFrame("relative=top");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue($this->isTextPresent("Widget updated correctly"), "Update widget header.");

        // check if appear at frontend
        $this->open(osc_base_url(true));
        $this->assertTrue($this->isTextPresent('New Widget Header - NEW CONTENT'), "Check header widget at website.");

        $this->open(osc_admin_base_url(true));
        $this->click("link=Manage widgets");
        $this->waitForPageToLoad("10000");

        // remove widget
        $this->click("link=Delete");
        $this->click("xpath=//input[@id='widget-delete-submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue($this->isTextPresent("Widget removed correctly"), "Delete widget header.");
        $this->assertTrue(!$this->isTextPresent("header1"), "Check delete widget header.");
    }









}
