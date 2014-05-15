<?php

require_once dirname(__FILE__) . '/OsclassTest.php';
require_once TEST_SERVER_PATH . '/oc-load.php';

class OsclassTestAdmin extends OsclassTest
{

    protected function _login($user = TEST_ADMIN_USER, $pass = TEST_ADMIN_PASS)
    {
        $this->open(osc_admin_base_url());
        $this->waitForPageToLoad("30000");
        $this->type("id=user_login", $user);
        $this->type("id=user_pass", $pass);
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
    }

    protected function _logout()
    {
        $this->open(osc_admin_base_url());
        $this->click("link=Logout");
        $this->waitForPageToLoad("30000");
    }



}
