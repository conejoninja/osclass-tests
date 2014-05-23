<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminTools extends OsclassTestAdmin
{

    function testImportData()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("link=Tools");
        $this->click("link=Import data");
        $this->waitForPageToLoad("10000");
        $this->type("sql", TEST_ASSETS_PATH . 'test.sql');
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Import complete"), "Import a sql file.");

        $this->open( osc_admin_base_url(true) );
        $this->click("link=Tools");
        $this->click("link=Import data");
        $this->waitForPageToLoad("10000");
        $this->type("sql", TEST_ASSETS_PATH . 'test_restore.sql');
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("Import complete"), "Import a sql file.");
    }

    function testImportDataFail()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("link=Tools");
        $this->click("link=Import data");
        $this->waitForPageToLoad("10000");
        $this->type("sql", TEST_ASSETS_PATH . 'img_test1.gif');
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent("There was a problem importing data to the database"), "Import image as sql.");
    }

    function testBackupSql()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("link=Tools");
        $this->click("link=Backup data");
        $this->waitForPageToLoad("30000");
        $this->click("//input[@id='backup_sql']");
        $this->waitForPageToLoad("600000");
        $this->assertTrue($this->isTextPresent("Backup completed successfully"), "Backup database.");
        // REMOVE FILE
        foreach (glob(osc_base_path() . "Osclass_mysqlbackup.*") as $filename) {
            unlink($filename);
        }
    }

    function testBackupZip()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("link=Tools");
        $this->click("link=Backup data");
        $this->waitForPageToLoad("30000");
        $this->click("//input[@id='backup_zip']");
        $this->waitForPageToLoad("600000");
        $this->assertTrue($this->isTextPresent("Archived successfully!"), "Backup osclass.");
        // REMOVE FILE
        foreach (glob(osc_base_path() . "Osclass_backup.*") as $filename) {
            unlink($filename);
        }
    }

    function testMaintenance()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='tools_maintenance']");
        $this->waitForPageToLoad("30000");
        $maintenance = $this->isTextPresent("Maintenance mode is: ON");
        if(!$maintenance) {
            $this->click("//input[@value='Enable maintenance mode']");
            $this->waitForPageToLoad("300000");
            $this->assertTrue($this->isTextPresent("Maintenance mode is ON"), "Enabling maintenance mode");
        }

        $this->open( osc_base_url(true) );
        $this->assertTrue($this->isTextPresent("The website is currently undergoing maintenance"), "Check maintenance mode on public website");

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='tools_maintenance']");
        $this->waitForPageToLoad("30000");
        $this->click("//input[@value='Disable maintenance mode']");
        $this->waitForPageToLoad("300000");
        $this->assertTrue($this->isTextPresent("Maintenance mode is OFF"), "Disabling maintenance mode");

    }

    function testLocations()
    {

        $this->_login();
        $mItem = new Item();
        $aItems = $mItem->listAll();
        foreach($aItems as $item) {
            $res = $mItem->delete(array('pk_i_id' => $item['pk_i_id']));
            //$this->assertTrue($res, 'Item deleted ok');
        }
        $this->_loadItems();

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='tools_location']");
        $this->waitForPageToLoad("10000");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("600000");
        $complete = 0;
        $max_time_limit = 0; // Add a time limit of 10 minutes to execute this while, in other case is infinite (if the ajax to get the percent is wrong!)!
        while($complete!='100' && $max_time_limit<60) {
            sleep(20);
            $complete = $this->getText("//div/p/span[@id='percent']");
            $max_time_limit++;
        }
        $this->assertTrue($this->isTextPresent("100 % Complete"), "Re-calculating location stats");

        $countries = CountryStats::newInstance()->listCountries(">=");
        foreach($countries as $c) {
            if($c['country_code']=="ES") {
                $this->assertTrue(($c['items']==14), "Spain items (should be 14, ".$c['items']." found)");
            } else {
                $this->assertTrue(($c['items']==0), $c['country_name']." items (should be 0, ".$c['items']." found)");
            }
        }

        $regions = RegionStats::newInstance()->listRegions('%%%%', ">=");
        foreach($regions as $r) {
            if($r['region_name']=="Barcelona") {
                $this->assertTrue(($r['items']==8), "Barcelona items (should be 8, ".$r['items']." found)");
            } else if($r['region_name']=="Madrid") {
                $this->assertTrue(($r['items']==3), "Madrid items (should be 3, ".$r['items']." found)");
            } else if($r['region_name']=="Valencia") {
                $this->assertTrue(($r['items']==3), "Alicante items (should be 3, ".$r['items']." found)");
            } else {
                $this->assertTrue(($r['items']==0), $r['region_name']." items (should be 0, ".$r['items']." found)");
            }
        }

        $cities = CityStats::newInstance()->listCities(null, ">");
        foreach($cities as $c) {
            if($c['city_name']=="Terrassa") {
                $this->assertTrue(($c['items']==4), "Terrassa items (should be 4, ".$c['items']." found)");
            } else if($c['city_name']=="Balsareny") {
                $this->assertTrue(($c['items']==4), "Balsareny items (should be 4, ".$c['items']." found)");
            } else if($c['city_name']=="Alameda del Valle") {
                $this->assertTrue(($c['items']==3), "Alameda del Valle items (should be 3, ".$c['items']." found)");
            } else if($c['city_name']=="Agres") {
                $this->assertTrue(($c['items']==3), "Agres items (should be 3, ".$c['items']." found)");
            } else {
                $this->assertTrue(($c['items']==0), $c['city_name']." items (should be 0, ".$c['items']." found)");
            }
        }

        $mItem = new Item();
        $aItems = $mItem->listAll();
        foreach($aItems as $item) {
            $res = $mItem->delete(array('pk_i_id' => $item['pk_i_id']));
        }

    }



}
