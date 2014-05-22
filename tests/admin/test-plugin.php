<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminPlugin extends OsclassTestAdmin
{

    private $_plugin_name = 'plugins_breadcrumbs_2.0.zip';

    function testPluginsUpload()
    {
        // UPLOAD
        $this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='plugins_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("link=Add plugin");
        $this->waitForPageToLoad("10000");
        $this->type("package", TEST_ASSETS_PATH . $this->_plugin_name);
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("The plugin has been uploaded correctly"),"Upload plugin $this->_plugin_name");
    }

    function testPluginsInstall()
    {
        // INSTALL
        $this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='plugins_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/a[text()='Install']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Plugin installed"),"Install plugin $this->_plugin_name");
    }


    function testPluginsConfigure()
    {
        // CONFIGURE
        $this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='plugins_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/a[text()='Configure']");
        //$this->click("//table/tbody/tr/td/a[@href[contains(.,'Bread crumbs')] and text()='Configure']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Breadcrumbs Help"),"Configure plugin $this->_plugin_name");
    }

    function testPluginsDisable()
    {
        // DISABLE
        $this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='plugins_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/a[text()='Disable']");
        //$this->click("//table/tbody/tr/td/a[text()='Disable' and @href[contains(.,'Bread crumbs')]]");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Plugin disabled"),"Disable plugin $this->_plugin_name");
    }

    function testPluginsEnable()
    {
        // ENABLE
        $this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='plugins_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/a[text()='Enable']");
        //$this->click("//table/tbody/tr/td/a[text()='Enable' and @href[contains(.,'Bread crumbs')]]");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Plugin enabled"),"Enable plugin $this->_plugin_name");
    }

    function testPluginsUninstall()
    {
        // UNINSTALL
        $this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='plugins_manage']");
        $this->waitForPageToLoad("10000");
        $this->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/a[text()='Uninstall']");
        sleep(2);
        $this->click("//input[@id='uninstall-submit']");

        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Plugin uninstalled"),"Uninstall plugin $this->_plugin_name");
        $this->_deletePlugin();
    }

    private function _deletePlugin() {
        @chmod(CONTENT_PATH."plugins/breadcrumbs/index.php", 0777);
        @chmod(CONTENT_PATH."plugins/breadcrumbs/", 0777);
        osc_deleteDir(CONTENT_PATH . "plugins/breadcrumbs/");
    }



}
