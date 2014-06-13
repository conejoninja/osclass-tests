<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminMarket extends OsclassTestAdmin
{

    function testMarketPluginsPagination()
    {

        $this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='market_view_plugins']");
        $this->waitForPageToLoad("10000");

        $text = $this->getText("//h2[@class='section-title']");
        $count = $this->getXpathCount("//div[@class='mk-item mk-item-plugin']");
        $this->assertTrue(($count==9), "Correct number of market items");
        $p1 = $this->_getPluginName();

        if(preg_match('|([0-9]+) plugins|', $text, $match)) {
            $last = $this->getText("css=a[class=searchPaginationNonSelected]:last");
            $this->assertTrue(($last==ceil($match[1]/9)), "Pagination shows correct number of pages");
            $this->click("css=a[class=searchPaginationNonSelected]:last");
            $this->waitForPageToLoad("10000");

            $count = $this->getXpathCount("//div[@class='mk-item mk-item-plugin']");
            $this->assertTrue(($count==($match[1]-((ceil($match[1]/9)-1)*9))), "Correct number of market items");
            $p2 = $this->_getPluginName();
            $this->assertFalse(($p1==$p2 || strpos($p2, "OR: Element")), "Same item in both pages, page didn't changed ( ".$p1." - ".$p2." )");

        } else {
            $this->assertTrue(false, "preg_match 'XX plugins' failed");
        }
    }

    function testMarketPluginsViewInfo()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='market_view_plugins']");
        $this->waitForPageToLoad("10000");
        $text = $this->_getPluginName();
        $this->assertFalse(strpos($text, "OR: Element"), "Market : View info failed");
    }

    function testMarketPluginsInstall()
    {
        osc_check_plugins_update(true);
        $old_plugins = json_decode(osc_get_preference('plugins_downloaded'));

        $this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='market_view_plugins']");
        $this->waitForPageToLoad("10000");

        $this->click("//div[@class='mk-item mk-item-plugin']/div/div/span[@class='download-btn']");
        sleep(2);
        $textIsPresent = false;
        for($t=0;$t<120;$t++) {
            sleep(2);
            $textIsPresent = $this->isTextPresent("The plugin has been downloaded correctly, proceed to install and configure");
            if($textIsPresent) { break; };
        }
        sleep(10);
        $this->assertTrue($textIsPresent, "Plugin failed downloading");
        sleep(1);
        $this->click("//div[@id='downloading']/div/p/a[contains(.,'Ok')]");//"//div[@='osc-modal-content']/p/a[@class='btn btn-mini btn-green']");


        // GET INFO OF NEW PLUGIN
        osc_check_plugins_update(true);
        $plugins = json_decode(osc_get_preference('plugins_downloaded'));
        foreach($old_plugins as $p) {
            foreach($plugins as $k => $v) {
                if($p==$v) {
                    unset($plugins[$k]);
                    break;
                }
            }
        }
        $info = array();
        $plugin = current($plugins);

        $plugins = Plugins::listAll(false);
        foreach($plugins as $p) {
            $pinfo = Plugins::getInfo($p);
            if($pinfo['short_name']==$plugin) {
                $info = $pinfo;
                break;
            }
        }

        // CHECK IT'S ON THE INSTALLED LIST
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='plugins_manage']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue($this->isTextPresent(@$info['plugin_name']), "Plugin does not appear on the list");

        // DELETE FOLDER
        $tmp = explode("/", preg_replace('|\/|', '/', "/".$info['filename']));
        $this->_deletePlugin($tmp[1]);
        osc_check_plugins_update(true);

        // CHECK IT'S *NOT* ON THE INSTALLED LIST
        /*$this->click("//a[@id='plugins_manage']");
        $this->waitForPageToLoad("10000");
        $this->assertFalse($this->isTextPresent(@$info['plugin_name']), "Plugin does appear on the list - FALSE POSITIVE / TEST have no permissions to delete plugin's folder -");*/


    }

    function testMarketOrderUpdate()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='market_view_plugins']");
        $this->waitForPageToLoad("10000");

        // get first item
        $last_update = '';
        $this->click("xpath=(//div[@class='mk-info']/div[@class='market-actions']/span[@class='more'])[1]");
        sleep(1);
        $last_update = $this->getText("xpath=//span[contains(.,'Last update ')]");

        // parse date
        $last_update = str_replace('Last update ', '', $last_update);
        $first_date  = $this->_createDate($last_update);

        // go to last page
        //$this->click("xpath=//span[@class='ui-dialog-title']/../a");
        $this->click("xpath=(//div[@class='has-pagination']/ul/li/a[@class='searchPaginationNonSelected'])[last()]");
        $this->waitForPageToLoad("10000");

        // get last item
        $last_update = '';
        $this->click("xpath=(//div[@class='mk-info']/div[@class='market-actions']/span[@class='more'])[last()]");
        sleep(1);
        $last_update = $this->getText("xpath=//span[contains(.,'Last update ')]");

        // parse date
        $last_update = str_replace('Last update ', '', $last_update);
        $last_date   = $this->_createDate($last_update);

        // comprobar que la fecha_uno es mayor que la fecha_dos
        // error_log('=>    '.$first_date.'  '.$last_date);
        $this->assertTrue( strtotime($first_date) >= strtotime($last_date) , 'last item is newer than first item');

        /*
         *  ------------------------ reverse order ------------------------
         */
        //$this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='market_view_plugins']");
        $this->waitForPageToLoad("10000");

        // change order ...
        $this->click("xpath=//a[@id='sort_updated']");
        $this->waitForPageToLoad("10000");

        // get first item
        $last_update = '';
        $this->click("xpath=(//div[@class='mk-info']/div[@class='market-actions']/span[@class='more'])[1]");
        sleep(1);
        $last_update = $this->getText("xpath=//span[contains(.,'Last update ')]");

        // parse date
        $last_update = str_replace('Last update ', '', $last_update);
        $first_date  = $this->_createDate($last_update);

        // go to last page
        //$this->click("xpath=//span[@class='ui-dialog-title']/../a");
        $this->click("xpath=(//div[@class='has-pagination']/ul/li/a[@class='searchPaginationNonSelected'])[last()]");
        $this->waitForPageToLoad("10000");

        // get last item
        $last_update = '';
        $this->click("xpath=(//div[@class='mk-info']/div[@class='market-actions']/span[@class='more'])[last()]");
        sleep(1);
        $last_update = $this->getText("xpath=//span[contains(.,'Last update ')]");

        // parse date
        $last_update = str_replace('Last update ', '', $last_update);
        $last_date   = $this->_createDate($last_update);

        // comprobar que la fecha_uno es mayor que la fecha_dos
        // error_log('=>    '.$first_date.'  '.$last_date);
        $this->assertTrue( strtotime($first_date) <= strtotime($last_date) , 'last item is older than first item');

    }

    function testMarketOrderDownload()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='market_view_plugins']");
        $this->waitForPageToLoad("10000");

        // change order ...
        $this->click("xpath=//a[@id='sort_download']");
        $this->waitForPageToLoad("10000");

        // get first item
        $downloads   = $this->getText("xpath=(//span[@class='downloads']/strong)[1]");

        // go to last page
        $this->click("xpath=(//div[@class='has-pagination']/ul/li/a[@class='searchPaginationNonSelected'])[last()]");
        $this->waitForPageToLoad("10000");

        // get last item
        $last_downloads = $this->getText("xpath=(//span[@class='downloads']/strong)[last()]");

        // check total downloads
        $this->assertTrue( $downloads >= $last_downloads , 'last item have more downloads than first item');

        /*
         *  ------------------------ reverse order ------------------------
         */
        //$this->_login();
        $this->open( osc_admin_base_url(true) ) ;
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='market_view_plugins']");
        $this->waitForPageToLoad("10000");

        // change order ... twice
        $this->click("xpath=//a[@id='sort_download']");
        $this->waitForPageToLoad("10000");
        $this->click("xpath=//a[@id='sort_download']");
        $this->waitForPageToLoad("10000");

        // get first item
        $downloads   = $this->getText("xpath=(//span[@class='downloads']/strong)[1]");

        // go to last page
        //$this->click("xpath=//span[@class='ui-dialog-title']/../a");
        $this->click("xpath=(//div[@class='has-pagination']/ul/li/a[@class='searchPaginationNonSelected'])[last()]");
        $this->waitForPageToLoad("10000");

        // get last item
        $last_downloads = $this->getText("xpath=(//span[@class='downloads']/strong)[last()]");

        $this->assertTrue( $downloads <= $last_downloads, 'last item have less downloads than first item');
    }


    private function _createDate($date) {
        $aDate  = explode('-', $date);
        $date   = date("Y-m-d", mktime(0,0,0,intval($aDate[1]), intval($aDate[2]), intval($aDate[0])) );
        return $date;
    }

    private function _getPluginName() {
        $this->click("//div[@class='mk-item mk-item-plugin']/div/div/span[@class='more']");
        sleep(3);
        $text =  $this->getText("//div[@class='mk-info']/table/tbody/tr/td/h3");
        $this->click("//button[@title='close']");
        return $text;
    }

    private function _deletePlugin($folder) {
        if(trim($folder)=='') { return false; }
        $this->_rchmod(CONTENT_PATH . "plugins/" . $folder);
        osc_deleteDir(CONTENT_PATH . "plugins/" . $folder);
    }

    private function _rchmod($path = '.', $level = 0 ) {
        $ignore = array('.', '..');
        $dh = @opendir( $path );
        while( false !== ( $file = readdir( $dh ) ) ) {
            if( !in_array( $file, $ignore ) ){
                @chown($path.'/'.$file,getmyuid());
                @chmod($path.'/'.$file,0777);
                if( is_dir( $path.'/'.$file ) ){
                    $this->_rchmod( $path.'/'.$file, ($level+1));
                }
            }
        }
        closedir( $dh );
    }


}
