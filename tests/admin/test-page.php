<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminPage extends OsclassTestAdmin
{

    public function testInsert()
    {
        $this->_login();

        //$this->_deletePage('test_page_example', false);
        $this->_newPage('test_page_example');
        $this->assertTrue($this->isTextPresent('The page has been added'), "Insert page.");
        $this->_deletePage('test_page_example');

        // complex title & description
        $this->_newPageWithData('test_page_example', "cos's test", "cos's test");
        $this->assertTrue($this->isTextPresent('The page has been added'), "Insert page.");
        $this->_checkPageData('test_page_example', "cos's test", "cos's test");
        $this->_deletePage('test_page_example');

        $this->_newPageWithData('test_page_example', "cos\'s \/test", 'cos\'s \"\'\test');
        $this->assertTrue($this->isTextPresent('The page has been added'), "Insert page.");
        $this->_checkPageData('test_page_example', "cos\'s \/test", 'cos\'s \"\'\test');
        $this->_deletePage('test_page_example');

        $this->_logout();
    }

    function testPagesInsertDuplicate()
    {
        $this->_login();
        $this->_newPageWithData('test_page_example',"just a title", "just a description");
        $this->assertTrue($this->isTextPresent('The page has been added'), "Insert page.");
        $this->_newPageWithData('test_page_example',"another title", "another description");
        $this->assertTrue($this->isTextPresent('Oops! That internal name is already in use'), "Insert page.");
        $this->assertTrue(($this->getValue("en_US#s_title")=='another title'), "Insert page. KEEP FORM");
        $this->assertTrue((stripos($this->getValue("en_US#s_text"),'another description')!==false), "Insert page. KEEP FORM");
        $this->_deletePage('test_page_example');
    }

    function testPageEdit()
    {
        $this->_login();
        $this->_newPageWithData('test_page_example',"cos's test", "cos's test");
        $this->assertTrue($this->isTextPresent('The page has been added'), "Insert page.");
        $this->_checkPageData('test_page_example', "cos's test", "cos's test");

        $this->_editPage('test_page_example', "foo's test\'", "description's \ sto", "new foo new");
        $this->_checkPageData('new-foo-new', "foo's test\'", "description's \ sto");
        $this->_deletePage('new-foo-new');
    }

    function testPageLinkOnFooter()
    {
        $this->_login();
        $this->_newPageWithData('test_page_example',"My page on the footer", "cos's test") ;
        $this->assertTrue($this->isTextPresent('The page has been added'), "Insert page.");
        $this->_checkPageData('test_page_example', "My page on the footer", "cos's test");

        $this->open(osc_base_url());
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent('My page on the footer'), "Check page on footer.");

        $this->open(osc_admin_base_url());
        $this->click("//a[@id='pages']");
        $this->waitForPageToLoad("30000");

        $this->click("//td[contains(.,'test_page_example')]/div[@class='actions']/ul/li/a[text()='Edit']");
        $this->waitForPageToLoad("30000");

        // editing page ...
        $this->click("b_link");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");
        sleep(1);
        $this->assertTrue($this->isTextPresent("The page has been updated"));

        $this->open(osc_base_url());
        $this->waitForPageToLoad("10000");
        $this->assertFalse($this->isTextPresent('My page on the footer'), "Check page on footer.");

        $this->_deletePage('test_page_example') ;
    }

    function testMultiplePagesInsert()
    {
        $this->_login() ;
        $count = 0;
        while( $count < 10 ) {
            $this->_newPage('test_page_example'.$count) ;
            $this->assertTrue($this->isTextPresent('The page has been added'), "Insert page.");
            $count++;
        }
        $this->_selectAndDelete('test_page_example', 0, 5);
        $this->_selectAllAndDelete();
    }

    public function testTableNavigation()
    {
        $this->_login() ;

        $count = 0;
        while( $count < 15 ) {
            $this->_newPage('test_page_example'.$count) ;
            $this->assertTrue($this->isTextPresent('The page has been added'), "Insert page.");
            $count++;
            flush();
        }

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='pages']");
        $this->waitForPageToLoad("10000");
        $res = $this->getXpathCount("//table[@class='table']/tbody/tr");
        $this->assertTrue(10==$res,"10 rows does not appear [$res]");
        $this->click("//a[@class='searchPaginationNext list-last']");
        $this->waitForPageToLoad("10000");

        $res = $this->getXpathCount("//table[@class='table']/tbody/tr");
        $this->assertTrue(5==$res,"5 rows does not appear [$res]");

        // two pages
        $this->_selectAllAndDelete();
        $this->_selectAllAndDelete();
    }

    private function _newPage($internal_name)
    {
        $this->open( osc_admin_base_url(true) );
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='pages']");
        $this->waitForPageToLoad("10000");
        $this->click("link=Add new");
        $this->waitForPageToLoad("10000");
        $this->type("s_internal_name", $internal_name );
        $this->type("en_US#s_title", "title US");

        $this->selectFrame("en_US#s_text_ifr");
        $this->focus("tinymce");
        $this->type("tinymce", "text for US");
        $this->selectWindow(null);

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");
    }

    private function _newPageWithData($internal_name, $title, $description)
    {
        $this->open( osc_admin_base_url(true) );
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='pages']");
        $this->waitForPageToLoad("10000");
        $this->click("link=Add new");
        $this->waitForPageToLoad("10000");

        $this->type("s_internal_name", $internal_name );
        $this->type("en_US#s_title", $title);

        $this->selectFrame("en_US#s_text_ifr");
        $this->focus("tinymce");
        $this->type("tinymce", $description);
        $this->selectWindow(null);

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");
    }

    private function _deletePage($internal_name, $check = true)
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='pages']");
        $this->waitForPageToLoad("30000");

        $this->click("//td[contains(.,'$internal_name')]/div[@class='actions']/ul/li/a[text()='Delete']");
        sleep(2);
        $this->click("//input[@id='page-delete-submit']");

        // click alert OK

        $this->waitForPageToLoad("30000");

        if($check) {
            $this->assertTrue($this->isTextPresent('One page has been deleted correctly') );
        }
    }

    private function _checkPageData($internal_name, $title, $description)
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='pages']");
        $this->waitForPageToLoad("30000");

        $this->click("//td[contains(.,'$internal_name')]/div[@class='actions']/ul/li/a[text()='Edit']");
        $this->waitForPageToLoad("30000");

        // check title & description
        $value = $this->getValue('en_US#s_title');
        $this->assertTrue($value==$title, "Title present");

        $this->selectFrame('en_US#s_text_ifr');
        $value = $this->getText('tinymce');
        $this->selectWindow(null);
        $this->assertTrue($value==$description, "Description present");
    }

    private function _editPage($internal_name, $title, $description, $new_internal_name = null)
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='pages']");
        $this->waitForPageToLoad("30000");

        $this->click("//td[contains(.,'$internal_name')]/div[@class='actions']/ul/li/a[text()='Edit']");
        $this->waitForPageToLoad("30000");

        // editing page ...
        if($new_internal_name!=null) { $this->type("s_internal_name", $new_internal_name); }
        $this->type("en_US#s_title", $title);

        // editing description tinymce
        $this->selectFrame("en_US#s_text_ifr");
        $this->focus("tinymce");
        $this->type("tinymce", $description);
        $this->selectWindow(null);


        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");
        sleep(1);
        $this->assertTrue($this->isTextPresent("The page has been updated"));
    }

    private function _selectAndDelete($internal_name, $beg, $fin)
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='pages']");
        $this->waitForPageToLoad("30000");

        $beg_ = $beg;
        for($beg_; $beg_ <= $fin-1; $beg_++){
            $this->click("//table/tbody/tr[contains(.,'$internal_name".$beg_."')]/td/input");
        }

        $this->select("//select[@name='action']", "label=Delete");
        $this->click("//input[@id='bulk_apply']");
        sleep(2);
        $this->click("//a[@id='bulk-actions-submit']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue($this->isTextPresent( ($fin-$beg) . " pages have been deleted correctly"));
    }

    private function _selectAllAndDelete()
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='pages']");
        $this->waitForPageToLoad("30000");


        $this->click("//input[@id='check_all']");
        $this->select("//select[@name='action']", "label=Delete");
        $this->click("//input[@id='bulk_apply']");
        sleep(2);
        $this->click("//a[@id='bulk-actions-submit']");
        $this->waitForPageToLoad("30000");
        // "regexpi:This is SeleniumWiki.com"
        $this->assertTrue($this->isTextPresent( "regexpi:pages have been deleted correctly"));
    }
    

}
