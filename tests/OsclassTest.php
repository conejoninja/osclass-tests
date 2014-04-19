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


}
?>