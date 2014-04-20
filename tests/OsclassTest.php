<?php

require_once dirname(dirname(__FILE__)) . '/config.php';
define('TEST_ASSETS_PATH', dirname(__FILE__) . '/assets/');

class OsclassTest extends PHPUnit_Extensions_SeleniumTestCase
{

    protected $captureScreenshotOnFailure = TRUE;
    protected $screenshotPath = TEST_IMAGE_PATH;
    protected $screenshotUrl = TEST_IMAGE_URL;


    protected function setUp()
    {
        $this->setBrowser("*chrome");
        $this->setBrowserUrl(TEST_SERVER_URL);
    }

    protected function _lastItemId()
    {
        // get last id from t_item.
        $item   = Item::newInstance()->dao->query('select pk_i_id from '.DB_TABLE_PREFIX.'t_item order by pk_i_id DESC limit 0,1');
        $aItem  = $item->result();
        return $aItem[0]['pk_i_id'];
    }



}
?>