<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminModerator extends OsclassTestAdmin
{

    function testModeratorMenu()
    {
        // insert new moderator admin
        User::newInstance()->insert(array(
            's_name' => 'Test Admin',
            's_username' => 'testmoderator',
            's_password' => sha1(TEST_ADMIN_PASS),
            's_secret' => 'mvqdnrpt',
            's_email' => 'testing+moderator@osclass.org',
            'e_permission' => 'MODERATOR'
        ));

        $this->_login( 'testmoderator', TEST_ADMIN_PASS) ;

        $this->open( osc_admin_base_url(true) );
        $this->waitForPageToLoad("2000");
        // check Admin Menu
        $res = $this->getXpathCount("//ul[@class='oscmenu']/li");
        $this->assertTrue(4==$res, "4 Menu options");

        $res = $this->getXpathCount("//ul[@class='oscmenu']/li[@id='menu_dash']/ul/li");
        $this->assertTrue(0==$res, "0 Submenu options under id=menu_dash");

        $res = $this->getXpathCount("//ul[@class='oscmenu']/li[@id='menu_items']/ul/li");
        $this->assertTrue(5==$res, "5 Submenu options under id=menu_items");

        $res = $this->getXpathCount("//ul[@class='oscmenu']/li[@id='menu_users']/ul/li");
        $this->assertTrue(2==$res, "2 Submenu options under id=menu_users");

        // try to enter to restricted zone
        $this->open(osc_admin_base_url(true).'?page=items&action=settings');
        $this->waitForPageToLoad("2000");
        $this->assertTrue($this->isTextPresent("You don't have enough permissions"), "Don't have enough permissions" ) ;

        $this->_logout();
        $this->open(osc_admin_base_url(true).'?page=settings');
        $this->waitForPageToLoad("2000");
        $this->assertFalse($this->isTextPresent("You don't have enough permissions"), "Don't show the text: 'Don't have enough permissions'" ) ;

        // remove user testmoderator!
        User::newInstance()->delete(array('s_username' => 'testmoderator') );
    }
    
}
