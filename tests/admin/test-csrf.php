<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestCSRF extends OsclassTestAdmin
{

    function testCsrfRedirect()
    {
        $url_invalid_request = "?page=ajax&action=enable_category&CSRFName=&CSRFToken=&id=1&enabled=0";
        // Probable invalid request
        $this->open( osc_admin_base_url(true) . $url_invalid_request );
        $this->waitForPageToLoad("30000");
        $this->assertTrue($this->isTextPresent("Probable invalid request"), 'Testing, CSRFName, CSRFToken empty.');
        $this->assertTrue($this->isTextPresent('{"error":1,"msg":"Probable invalid request."}'), 'no json');

        $url_invalid_token   = "?page=ajax&action=enable_category&CSRFName=foo&CSRFToken=bar&id=1&enabled=0";
        // Invalid CSRF token
        $this->open( osc_admin_base_url(true) . $url_invalid_token );
        $this->waitForPageToLoad("30000");
        $this->assertTrue($this->isTextPresent("Invalid CSRF token"), 'Testing, CSRFName, CSRFToken incorrect.');
        $this->assertTrue($this->isTextPresent('{"error":1,"msg":"Invalid CSRF token."}'), 'no json');

    }

}
