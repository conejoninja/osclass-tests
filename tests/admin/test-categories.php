<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminCategories extends OsclassTestAdmin
{

    function _testCategory_updateExpiration()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("link=Categories");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Categories"),"Categories ...");

        // add item at subcategory 'Cars'
        $this->_addItem();
        $itemId = $this->_lastItemId();
        // hardcoded - update dt_pub_date
        Item::newInstance()->update(array('dt_pub_date' => '2010-01-01 10:10:10'), array('pk_i_id' => $itemId));

        $this->open( osc_admin_base_url(true) );
        $this->click("link=Categories");
        $this->waitForPageToLoad("10000");
        //$this->click("xpath=//div[@class='name-cat'][contains(.,'Vehicles')]/span[@class='toggle' and not(contains(@style,'display:none'))]");
        //sleep(1);
        $this->click("xpath=//div[@class='category_row' and contains(.,'Cars')]/div[@class='actions-cat']/a[text()='Edit']");
        sleep(2);
        $this->type('i_expiration_days', 5);
        $this->click("xpath=//input[@value='Save changes']");
        sleep(2);

        // check
        $item = $this->_lastItem();

        $this->assertTrue($item['dt_expiration'] == '2010-01-06 10:10:10', 'Check dt_expiration at t_item');

        Item::newInstance()->update(array('dt_expiration' => (date('Y')+1).'-01-01 10:10:10'), array('pk_i_id' => $itemId));
        Item::newInstance()->deleteByPrimaryKey($itemId);
    }

    function _testCategory_createCategory()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("link=Categories");
        $this->waitForPageToLoad("10000");
        $this->assertFalse($this->isTextPresent("NEW CATEGORY, EDIT ME!"),"Create category");
        $this->click("link=Add");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("NEW CATEGORY, EDIT ME!"),"Create category");
        $this->click("xpath=//div[@class='category_row' and contains(.,'NEW CATEGORY')]/div[@class='actions-cat']/a[text()='Delete']");
        sleep(2);
        $this->click("//a[@id='category-delete-submit']");
        sleep(2);
        $this->assertTrue($this->isTextPresent("Saved"),"Create category");
    }


    function testCategory_enableDisableCategory()
    {

        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("link=Categories");
        $this->waitForPageToLoad("10000");
        $this->assertFalse($this->isTextPresent("NEW CATEGORY, EDIT ME!"),"Check NEW CATEGORY does not exists");
        $this->click("link=Add");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("NEW CATEGORY, EDIT ME!"),"Create category");
        $this->assertFalse($this->isTextPresent("Enable"),"Check enable is not present");
        $this->click("xpath=//div[@class='category_row' and contains(.,'NEW CATEGORY')]/div[@class='actions-cat']/a[text()='Disable']");
        sleep(2);
        $this->assertTrue($this->isTextPresent("The category as well as its subcategories have been disabled"),"Category disabled");
        $this->assertTrue($this->isTextPresent("Enable"),"Check enable is present");
        $this->click("xpath=//div[@class='category_row' and contains(.,'NEW CATEGORY')]/div[@class='actions-cat']/a[text()='Enable']");
        sleep(2);
        $this->assertTrue($this->isTextPresent("The category as well as its subcategories have been enabled"),"Category disabled");
        $this->assertFalse($this->isTextPresent("Enable"),"Check enable is present");


        $this->click("xpath=//div[@class='category_row' and contains(.,'NEW CATEGORY')]/div[@class='actions-cat']/a[text()='Delete']");
        sleep(2);
        $this->click("//a[@id='category-delete-submit']");

        sleep(2);
        $this->assertTrue($this->isTextPresent("Saved"),"Create category");

    }
    
}
