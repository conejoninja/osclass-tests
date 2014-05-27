<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminReported extends OsclassTestAdmin
{

    function testInsertItem()
    {
        $this->_login();
        // insert 4 items
        $this->_addItem();
        $this->_addItem();
        $this->_addItem();
        $this->_addItem();

        // mark as spam item 1, 2, 3, 4
        $this->_markAs('spam',array(1,2,3,4) );
        // mark as bad item 1 & 3
        $this->_markAs('bad',array(1,3,4) );
        // mark as expire item 1 & 3
        $this->_markAs('exp',array(4) );

        // go to admin reported listings
        // and sort the table by spam and bad
        // checkOrder($type, $count)
        $this->_checkOrder('spam', 4 );
        $this->_checkOrder('bad' , 3 );
        $this->_checkOrder('exp' , 1 );

        // unmark 1 as spam
        $this->_unmarkAs('spam', array(2));
        $this->_checkOrder('spam', 3 );
        // unmark 1 as spam
        $this->_unmarkAs('bad', array(1));
        $this->_checkOrder('bad', 2 );
        // unmark 1 as ALL
        $this->_unmarkAs('all', array(1));
        $this->_checkOrder('all', 3 );
    }

    function testBulkaction()
    {
        $this->_login();
        // still having 4 items ...
        // clear all stats
        // check no results on reported
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_reported']");
        $this->waitForPageToLoad("10000");
        sleep(1);
        // select all
        $this->click("xpath=//input[@id='check_all']");
        $this->select('bulk_actions', 'value=clear_all');
        $this->click("xpath=//input[@id='bulk_apply']");
        $this->click("xpath=//a[@id='bulk-actions-submit']");

        $this->_checkOrder('all', 1);
        $this->assertTrue($this->isTextPresent("No data available in table"), "BulkActions clear spam. ERROR");
        // markas XX + YY
        $this->_markAs('spam', array(1,2));
        $this->_markAs('exp', array(2));

        $this->_checkOrder('spam', 2);
        $this->_checkOrder('exp',  1);
        // bulkAction unmark as XX
        // check results on results
        $this->_bulkAction('exp');
        $this->_checkOrder('exp', 1);
        $this->assertTrue($this->isTextPresent("No data available in table"), "BulkActions clear expired. ERROR");
        // bulkAction unmark as YY
        // check no results on reported
        $this->_bulkAction('spam');
        $this->_checkOrder('all', 1);
        $this->assertTrue($this->isTextPresent("No data available in table"), "BulkActions clear all. ERROR");
    }
    
    function testRemoveAllItems()
    {
        $this->_login();

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_manage']");
        $this->waitForPageToLoad("10000");
        sleep(2); // time enough to load table data
        $num = $this->getXpathCount('//table/tbody/tr');

        $loops = 0;
        while( $loops < 5 && $num >= 1 ) {
            $this->click("xpath=//input[@id='check_all']");
            $this->select('bulk_actions', 'value=delete_all');
            $this->click("xpath=//input[@id='bulk_apply']");
            $this->click("xpath=//a[@id='bulk-actions-submit']");
            $this->waitForPageToLoad("10000");
            $this->assertTrue($this->isTextPresent("listings have been deleted") || $this->isTextPresent("listing has been deleted")
                , "BulkActions delete all on delete test. ERROR");

            $num = $this->getXpathCount('//table/tbody/tr');
            $loops++;
            if($this->isTextPresent("No data available in table") ) {
                break;
            }
        }
    }

    private function _unmarkAs($type, $array)
    {
        $xpath_str = "//table/tbody/tr[position()=_ID_]/td/div/ul/li/a[contains(.,'_ACTION_')]";
        foreach($array as $id) {

            $new_xpath = str_replace('_ID_', $id, $xpath_str);

            $this->open( osc_admin_base_url(true) );
            $this->waitForPageToLoad("10000");
            $this->click("//a[@id='items_reported']");
            $this->waitForPageToLoad("10000");
            sleep(1);
            switch ($type) {
                case 'spam':
                    // sort by
                    $this->click("//a[@id='order_spam']");
                    sleep(3);
                    $new_xpath = str_replace('_ACTION_', 'Clear Spam', $new_xpath);
                    $this->click($new_xpath);
                    sleep(3);
                    $this->assertTrue($this->isTextPresent("The listing has been unmarked as spam"), "Can't unmark spam. ERROR");
                    break;
                case 'exp':
                    $this->click("//a[@id='order_exp']");
                    sleep(3);
                    $new_xpath = str_replace('_ACTION_', 'Clear Expired', $new_xpath);
                    $this->click($new_xpath);
                    sleep(3);
                    $this->assertTrue($this->isTextPresent("The listing has been unmarked as expired"), "Can't unmark spam. ERROR");
                    break;
                case 'bad':
                    $this->click("//a[@id='order_bad']");
                    sleep(3);
                    $new_xpath = str_replace('_ACTION_', 'Clear Misclassified', $new_xpath);
                    $this->click($new_xpath);
                    sleep(3);
                    $this->assertTrue($this->isTextPresent("The listing has been unmarked as bad"), "Can't unmark spam. ERROR");
                    break;
                case 'all':
                    $this->click("//a[@id='order_date']");
                    sleep(3);
                    $new_xpath = str_replace('_ACTION_', 'Clear All', $new_xpath);
                    $this->click($new_xpath);
                    sleep(15);
                    $this->assertTrue($this->isTextPresent("The listing has been unmarked"), "Can't unmark ALL. ERROR");
                    break;
                default:
                    break;
            }
        }
    }

    private function _checkOrder($type, $count)
    {
        $this->open( osc_admin_base_url(true) );
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='items_reported']");
        $this->waitForPageToLoad("10000");
        $num = 0;
        sleep(1);
        switch ($type) {
            case 'spam':
                //error_log('case spam');
                $this->click("//a[@id='order_spam']");
                sleep(1);
                $num = $this->getXpathCount('//table/tbody/tr');
                $this->assertTrue( ($num == $count) , 'There are the correct rows SPAM ( '.$num.' == '.$count.' )');
                break;
            case 'exp':
                //error_log('case exp');
                $this->click("//a[@id='order_exp']");
                sleep(1);
                $num = $this->getXpathCount('//table/tbody/tr');
                $this->assertTrue( ($num == $count) , 'There are the correct rows EXPIRED ( '.$num.' == '.$count.' )');
                break;
            case 'bad':
                //error_log('case bad');
                $this->click("//a[@id='order_bad']");
                sleep(1);
                $num = $this->getXpathCount('//table/tbody/tr');
                $this->assertTrue( ($num == $count) , 'There are the correct rows BAD ( '.$num.' == '.$count.' )');
                break;
            case 'all':
                //error_log('case all');
                $num = $this->getXpathCount('//table/tbody/tr');
                $this->assertTrue( ($num == $count) , 'There are the correct rows (ALL) ( '.$num.' == '.$count.' )');
                break;
            default:
                break;
        }
        //error_log($num . " == " . $count);
    }

    private function _markAs($type, $array)
    {
        $xpath_str = "xpath=//table/tbody/tr[position()=_ID_]/td/a[contains(.,'title item')]@href";
        foreach($array as $id) {
            // go to reported listings
            $this->open( osc_admin_base_url(true) );
            $this->waitForPageToLoad("10000");
            $this->click("//a[@id='items_manage']");
            $this->waitForPageToLoad("10000");
            sleep(2);
            $new_xpath = str_replace('_ID_', $id, $xpath_str);
            $href = $this->getAttribute($new_xpath);

            $this->open($href);
            $this->waitForPageToLoad("10000");
            sleep(2);
            // item detail -> mark as XXX
            switch ($type) {
                case 'spam':
                    $this->select("as", "label=regexp:\\s*Mark as spam");
                    $this->waitForPageToLoad("10000");
                    $this->assertTrue($this->isTextPresent("Thanks! That's very helpful"), 'Item has been marked');
                    break;
                case 'exp':
                    $this->select("as", "label=regexp:\\s*Mark as expired");
                    $this->waitForPageToLoad("10000");
                    $this->assertTrue($this->isTextPresent("Thanks! That's very helpful"), 'Item has been marked');
                    break;
                case 'bad':
                    $this->select("as", "label=regexp:\\s*Mark as misclassified");
                    $this->waitForPageToLoad("10000");
                    $this->assertTrue($this->isTextPresent("Thanks! That's very helpful"), 'Item has been marked');
                    break;
                default:
                    break;
            }
        }
    }

    private function _bulkAction($type)
    {
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='items_reported']");
        $this->waitForPageToLoad("10000");
        sleep(1);
        // select all
        $this->click("xpath=//input[@id='check_all']");

        switch ($type) {
            case 'spam':
                $this->select('bulk_actions', 'value=clear_spam_all');
                $this->click("xpath=//input[@id='bulk_apply']");
                $this->click("xpath=//a[@id='bulk-actions-submit']");
                $this->waitForPageToLoad("10000");
                $this->assertTrue(
                    $this->isTextPresent("listings have been unmarked as spam") || $this->isTextPresent("listing has been unmarked as spam")
                    , "BulkActions clear spam. ERROR");
                break;
            case 'exp':
                $this->select('bulk_actions', 'value=clear_expi_all');
                $this->click("xpath=//input[@id='bulk_apply']");
                $this->click("xpath=//a[@id='bulk-actions-submit']");
                $this->waitForPageToLoad("10000");
                $this->assertTrue(
                    $this->isTextPresent("listings have been unmarked as expired") || $this->isTextPresent("listing has been unmarked as expired")
                    , "BulkActions clear expired. ERROR");
                break;
            case 'bad':
                $this->select('bulk_actions', 'value=clear_bad_all');
                $this->click("xpath=//input[@id='bulk_apply']");
                $this->click("xpath=//a[@id='bulk-actions-submit']");
                $this->waitForPageToLoad("10000");
                $this->assertTrue(
                    $this->isTextPresent("listings have been unmarked as missclassified") || $this->isTextPresent("listing has been unmarked as missclassified")
                    , "BulkActions clear bad. ERROR");
                break;
            case 'all':
                $this->select('bulk_actions', 'value=clear_all');
                $this->click("xpath=//input[@id='bulk_apply']");
                $this->click("xpath=//a[@id='bulk-actions-submit']");
                $this->waitForPageToLoad("10000");
                $this->assertTrue($this->isTextPresent("listings have been unmarked") || $this->isTextPresent("listing has been unmarked")
                    , "BulkActions clear all. ERROR");
                break;
            case 'delete':
                $this->select('bulk_actions', 'value=delete_all');
                $this->click("xpath=//input[@id='bulk_apply']");
                $this->click("xpath=//a[@id='bulk-actions-submit']");
                $this->waitForPageToLoad("10000");
                $this->assertTrue($this->isTextPresent("listings have been deleted") || $this->isTextPresent("listing has been deleted")
                    , "BulkActions delete all. ERROR");
                break;
            default:
                break;
        }
    }


}
