<?php
require_once dirname(dirname(__FILE__)).'/OsclassTestAdmin.php';
class TestAdminSettings extends OsclassTestAdmin
{

    function testCrontab()
    {
        $this->_login();
        $this->waitForPageToLoad("10000");
        $this->assertTrue(!$this->isTextPresent('Log in'), "Login oc-admin.");

        $this->open( osc_admin_base_url(true) );
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='settings_general']");
        $this->waitForPageToLoad("10000");

        $cron = osc_get_preference('auto_cron');
        if($cron == 1){ $cron = 'on';} else { $cron = 'off'; }

        $this->assertTrue($cron==$this->getValue("auto_cron"), "Cron tab, check values/ preference values.");

        $this->click("auto_cron");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");

        osc_reset_preferences();
        $cron = osc_get_preference('auto_cron');
        if($cron == 1){ $cron = 'on';} else { $cron = 'off'; }

        $this->assertTrue($cron==$this->getValue("auto_cron"), "Cron tab, check values/ preference values.");

        osc_reset_preferences();
    }

    function testMediatab()
    {
        $this->_login();

        $maxSizeKb      = osc_get_preference('maxSizeKb');
        $dimThumbnail   = osc_get_preference('dimThumbnail');
        $dimPreview     = osc_get_preference('dimPreview');
        $dimNormal      = osc_get_preference('dimNormal');
        $keep_original_image   = osc_get_preference('keep_original_image');
        osc_reset_preferences();
        if($keep_original_image == 1){ $keep_original_image = 'on';} else { $keep_original_image = 'off'; }

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_media']");
        $this->waitForPageToLoad("10000");

        // change values to sometest-defined ones
        $this->type('maxSizeKb'   , 'ads');
        $this->type('dimThumbnail', 'bsg');
        $this->type('dimPreview'  , 'cylon');
        $this->type('dimNormal'   , 'adama');
        $this->click("//input[@id='save_changes']");
        sleep(4);
        $this->assertTrue($this->isTextPresent("Maximum size: this field must only contain numeric characters"), "Media tab JS, update.");
        $this->assertTrue($this->isTextPresent("Thumbnail size: is not in the correct format"), "Media tab JS, update.");
        $this->assertTrue($this->isTextPresent("Preview size: is not in the correct format"), "Media tab JS, update.");
        $this->assertTrue($this->isTextPresent("Normal size: is not in the correct format"), "Media tab JS, update.");

        $this->type('maxSizeKb'   , '');
        $this->type('dimThumbnail', '');
        $this->type('dimPreview'  , '');
        $this->type('dimNormal'   , '');
        $this->click("//input[@id='save_changes']");
        sleep(4);
        $this->assertTrue($this->isTextPresent("Maximum size: this field is required"), "Media tab JS, update.");
        $this->assertTrue($this->isTextPresent("Thumbnail size: this field is required"), "Media tab JS, update.");
        $this->assertTrue($this->isTextPresent("Preview size: this field is required"), "Media tab JS, update.");
        $this->assertTrue($this->isTextPresent("Normal size: this field is required"), "Media tab JS, update.");

        $this->type('maxSizeKb'   , '500');
        $this->keyUp("maxSizeKb", "0");
        $this->type('dimThumbnail', '10x10');
        $this->keyUp("dimThumbnail", "0");
        $this->type('dimPreview'  , '50x50');
        $this->keyUp("dimPreview", "0");
        $this->type('dimNormal'   , '100x100');
        $this->keyUp("dimNormal", "0");
        $this->click('keep_original_image');
        sleep(2);

        $this->click("//input[@id='save_changes']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("Media config has been updated"), "Media tab, update.");

        $this->assertTrue( $this->getValue("maxSizeKb")      =='500', 'Media tab, check maxSizeKb');
        $this->assertTrue( $this->getValue('dimThumbnail')   =='10x10', 'Media tab, check dimThumnai 10x10');
        $this->assertTrue( $this->getValue('dimPreview')     =='50x50' , 'Media tab, check dimPreview 50x50');
        $this->assertTrue( $this->getValue('dimNormal')      =='100x100', 'Media tab, check dimNormal 100x100');
        $this->assertTrue( $this->getValue('keep_original_image')==($keep_original_image=='off'?'on':'off'), 'Media tab, check keep_original_image');

        $this->type('maxSizeKb'   , $maxSizeKb);
        $this->type('dimThumbnail', $dimThumbnail);
        $this->type('dimPreview'  , $dimPreview);
        $this->type('dimNormal'   , $dimNormal);
        $this->click('keep_original_image');

        $this->click("//input[@id='save_changes']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("Media config has been updated"), "Media tab, update.");

        $this->assertTrue( $this->getValue("maxSizeKb")      ==$maxSizeKb);
        $this->assertTrue( $this->getValue('dimThumbnail')   ==$dimThumbnail);
        $this->assertTrue( $this->getValue('dimPreview')     ==$dimPreview);
        $this->assertTrue( $this->getValue('dimNormal')      ==$dimNormal);
        $this->assertTrue( $this->getValue('keep_original_image')==$keep_original_image);




        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_media']");
        $this->waitForPageToLoad("10000");
        //$this->click("//input[@id='watermark_image']");
        sleep(2);
        /*$string = $this->_path(LIB_PATH."simpletest/test/osclass/img_test1.png");
        $l = strlen($string);
        for($s=0;$s<$l;$s++) {
            $this->keyDown("//input[@id='watermark_image_file']", substr($string, $s, 1));
        } */
        $this->type("//input[@id='watermark_image_file']", TEST_ASSETS_PATH . 'img_test1.png');
        //$this->click("//input[@id='watermark_image_file']");
        sleep(2);
        //sleep(20);
        //sleep(20);
        $this->click("//input[@id='watermark_image']");

        $this->click("//input[@id='save_changes']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("Media config has been updated"), "Media tab, update.");

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_media']");
        $this->waitForPageToLoad("10000");
        $this->click("//input[@id='watermark_image']");
        sleep(4);
        $this->type("//input[@name='watermark_image']", TEST_ASSETS_PATH . 'img_test2.png');
        $this->click("//input[@id='save_changes']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("Media config has been updated"), "Media tab, update.");

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_media']");
        $this->waitForPageToLoad("10000");
        $this->click("//input[@id='watermark_image']");
        sleep(4);
        $this->type("//input[@name='watermark_image']", TEST_ASSETS_PATH . 'img_test1.gif');
        $this->click("//input[@id='save_changes']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("The watermark image has to be a .PNG file"), "Media tab, update.");

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_media']");
        $this->waitForPageToLoad("10000");
        $this->click("//input[@id='watermark_image']");
        sleep(4);
        $this->type("//input[@name='watermark_image']", TEST_ASSETS_PATH . 'logo.jpg');
        $this->click("//input[@id='save_changes']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("The watermark image has to be a .PNG file"), "Media tab, update.");

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_media']");
        $this->waitForPageToLoad("10000");
        $this->click("//input[@id='watermark_none']");
        $this->click("//input[@id='save_changes']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue($this->isTextPresent("Media config has been updated"), "Media tab, update.");


        osc_reset_preferences();
    }

    function testMailServer()
    {
        $pref = array();
        $pref['mailserver_type']        = osc_set_preference('mailserver_type');
        $pref['mailserver_host']        = osc_set_preference('mailserver_host');
        $pref['mailserver_port']        = osc_set_preference('mailserver_port');
        $pref['mailserver_username']    = osc_set_preference('mailserver_username');
        $pref['mailserver_password']    = osc_set_preference('mailserver_password');
        $pref['mailserver_ssl']         = osc_set_preference('mailserver_ssl');
        $pref['mailserver_auth']        = osc_set_preference('mailserver_auth');
        if($pref['mailserver_auth'] == 1){ $pref['mailserver_auth'] = 'on';} else { $pref['mailserver_auth'] = 'off'; }
        osc_reset_preferences();

        $this->_login();

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_mailserver']");
        $this->waitForPageToLoad("10000");

        $this->type('mailserver_type'     , 'custom');
        $this->type('mailserver_host'     , 'mailserver.test.net');
        $this->type('mailserver_port'     , '1234');
        $this->type('mailserver_username' , 'test');
        $this->type('mailserver_password' , 'test');
        $this->type('mailserver_ssl'      , 'ssltest');
        $this->click('mailserver_auth');

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent( 'Mail server configuration has changed') , "Mail server configuration.");

        $this->assertTrue( $this->getValue("mailserver_type")     =='custom');
        $this->assertTrue( $this->getValue('mailserver_host')     =='mailserver.test.net');
        $this->assertTrue( $this->getValue('mailserver_port')     =='1234');
        $this->assertTrue( $this->getValue('mailserver_username') =='test');
        $this->assertTrue( $this->getValue('mailserver_password') =='test');
        $this->assertTrue( $this->getValue('mailserver_ssl')      =='ssltest');
        $this->assertTrue( $this->getValue('mailserver_auth')     ==$pref['mailserver_auth']);

        $this->type('mailserver_type'     , $pref['mailserver_type']);
        $this->type('mailserver_host'     , $pref['mailserver_host']);
        $this->type('mailserver_port'     , $pref['mailserver_port']);
        $this->type('mailserver_username' , $pref['mailserver_username']);
        $this->type('mailserver_password' , $pref['mailserver_password']);
        $this->type('mailserver_ssl'      , $pref['mailserver_ssl']);
        $this->click('mailserver_auth');

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $pref['mailserver_auth'] = $pref['mailserver_auth']=='on'?'off':'on';
        $this->assertTrue( $this->isTextPresent( 'Mail server configuration has changed') , "Mail server configuration.");

        $this->assertTrue( $this->getValue("mailserver_type")     ==$pref['mailserver_type']);
        $this->assertTrue( $this->getValue('mailserver_host')     ==$pref['mailserver_host']);
        $this->assertTrue( $this->getValue('mailserver_port')     ==$pref['mailserver_port']);
        $this->assertTrue( $this->getValue('mailserver_username') ==$pref['mailserver_username']);
        $this->assertTrue( $this->getValue('mailserver_password') ==$pref['mailserver_password']);
        $this->assertTrue( $this->getValue('mailserver_ssl')      ==$pref['mailserver_ssl']);
        $this->assertTrue( $this->getValue('mailserver_auth')     ==$pref['mailserver_auth']);

        osc_reset_preferences();
    }


    function testSpamAndBots()
    {
        $pref = array();
        $pref['akismet_key']        = osc_get_preference('akismetKey');
        $pref['recaptchaPrivKey']   = osc_get_preference('recaptchaPrivKey');
        $pref['recaptchaPubKey']    = osc_get_preference('recaptchaPubKey');

        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_spambots']");
        $this->waitForPageToLoad("10000");

        // AKISMET

        $this->type('akismetKey'          , '9f18f856aa3c');
        $this->click("//input[@id='submit_akismet']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Your Akismet key has been updated") ,"Can't update the Akismet Key. ERROR");
        $this->assertTrue( $this->getValue('akismetKey')=='9f18f856aa3c', 'Spam&Bots, akismet key');

        $this->type('akismetKey'          , $pref['akismet_key']);
        $this->click("//input[@id='submit_akismet']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Your Akismet key has been cleared") ,"Can't update the Akismet Key. ERROR");
        $this->assertTrue( $this->getValue('akismetKey')==$pref['akismet_key'] , 'Spam&Bots, akismet key');

        // RECAPTCHA

        $this->type('recaptchaPrivKey'    , '1234567890');
        $this->type('recaptchaPubKey'     , '1234567890');
        $this->click("//input[@id='submit_recaptcha']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Your reCAPTCHA key has been updated") ,"Can't update the reCAPTCHA Key. ERROR");
        $this->assertTrue( $this->getValue('recaptchaPrivKey')=='1234567890', 'Spam&Bots, recaptcha private key');
        $this->assertTrue( $this->getValue('recaptchaPubKey')=='1234567890', 'Spam&Bots, recaptcha public key');

        $this->type('recaptchaPrivKey'    , $pref['recaptchaPrivKey']);
        $this->type('recaptchaPubKey'     , $pref['recaptchaPubKey']);
        $this->click("//input[@id='submit_recaptcha']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Your reCAPTCHA key has been cleared") ,"Can't update the reCAPTCHA Key. ERROR");
        $this->assertTrue( $this->getValue('recaptchaPrivKey')==$pref['recaptchaPrivKey'] , 'Spam&Bots, recaptcha private key');
        $this->assertTrue( $this->getValue('recaptchaPubKey')==$pref['recaptchaPubKey'] , 'Spam&Bots, recaptcha public key');

        osc_reset_preferences();
    }

    function testComments()
    {
        $pref['enabled_comments']   = osc_get_preference('enabled_comments');
        $pref['moderate_comments']  = osc_get_preference('moderate_comments');
        $pref['notify_new_comment'] = osc_get_preference('notify_new_comment');
        $pref['reg_user_post_comments'] = osc_get_preference('reg_user_post_comments');
        $pref['num_moderate_comments'] = osc_get_preference('moderate_comments');
        $pref['comments_per_page']     = osc_get_preference('comments_per_page');

        if($pref['enabled_comments'] == 1){ $pref['enabled_comments'] = 'on';} else { $pref['enabled_comments'] = 'off'; }
        if($pref['moderate_comments'] < 0){ $pref['moderate_comments'] = 'off';} else { $pref['moderate_comments'] = 'on'; }
        if($pref['notify_new_comment'] == 1){ $pref['notify_new_comment'] = 'on';} else { $pref['notify_new_comment'] = 'off'; }
        if($pref['reg_user_post_comments'] == 1){ $pref['reg_user_post_comments'] = 'on';} else { $pref['reg_user_post_comments'] = 'off'; }

        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_comments']");
        $this->waitForPageToLoad("10000");

        $this->type("num_moderate_comments","wrong");
        $this->type("comments_per_page","test");
        $this->click("//input[@type='submit']");
        sleep(4);

        $this->assertTrue( $this->isTextPresent("Moderated comments: this field must only contain numeric characters") , "Comments settings JS validator.");
        $this->assertTrue( $this->isTextPresent("Comments per page: this field must only contain numeric characters") , "Comments settings JS validator.");


        $this->click("enabled_comments");
        $this->click("reg_user_post_comments");
        if( !$pref['moderate_comments'] == 'on' ) {
            $this->click("moderate_comments");
        }
        $this->click("notify_new_comment");
        $this->type("num_moderate_comments",10);
        $this->type("comments_per_page",0);

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Comment settings have been updated") , "Comments settings, check.");
        if( $pref['enabled_comments'] == 'on' ){
            $this->assertTrue( $this->getValue('enabled_comments')=='off' , "Comments settings, check." );
        } else {
            $this->assertTrue( $this->getValue('enabled_comments')=='on' , "Comments settings, check." );
        }

        if( $pref['reg_user_post_comments'] == 'on' ){
            $this->assertTrue( $this->getValue('reg_user_post_comments')=='off' , "Comments settings, check." );
        } else {
            $this->assertTrue( $this->getValue('reg_user_post_comments')=='on' , "Comments settings, check." );
        }

        if(! $pref['moderate_comments'] == 'on' ){
            $this->assertTrue( $this->getValue('moderate_comments')=='on' , "Comments settings, check." );
        }

        if( $pref['notify_new_comment'] == 'on' ){
            $this->assertTrue( $this->getValue('notify_new_comment')=='off' , "Comments settings, check." );
        } else {
            $this->assertTrue( $this->getValue('notify_new_comment')=='on' , "Comments settings, check." );
        }

        $this->assertTrue($this->getValue("num_moderate_comments") == 10 , "Comments settings, check. Not saved ok, num comments are 10." );
        $this->assertTrue($this->getValue("num_moderate_comments") == 10 , "Comments settings, check. Not saved ok, num comments are 10." );

        $this->click("enabled_comments");
        $this->click("reg_user_post_comments");
        $this->click("notify_new_comment");
        $this->type("num_moderate_comments",$pref['num_moderate_comments'] );
        $this->type("comments_per_page",$pref['comments_per_page'] );

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Comment settings have been updated") , "Update comments settings. ERROR");

        $this->assertTrue( $this->getValue('enabled_comments')       == $pref['enabled_comments']         , "Comments settings, check.");
        $this->assertTrue( $this->getValue('reg_user_post_comments') == $pref['reg_user_post_comments']   , "Comments settings, check.");
        $this->assertTrue( $this->getValue('notify_new_comment')     == $pref['notify_new_comment']       , "Comments settings, check.");
        $this->assertTrue( $this->getValue('num_moderate_comments')  == $pref['num_moderate_comments']    , "Comments settings, check.");
        $this->assertTrue( $this->getValue('comments_per_page')      == $pref['comments_per_page']        , "Comments settings, check.");

        osc_reset_preferences();
    }

    function testGeneralSettings()
    {
        $pref = $this->_getPreferencesGeneralSettings();

        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_general']");
        $this->waitForPageToLoad("10000");


        $this->type("pageTitle"   ,"");
        $this->type("contactEmail","");
        $this->type("num_rss_items" , "");
        $this->type("max_latest_items_at_home" , "");
        $this->type("default_results_per_page" , "");
        $this->click("//input[@type='submit']");
        sleep(4);

        $this->assertTrue( $this->isTextPresent("Page title: this field is required") , 'JS Validation');
        $this->assertTrue( $this->isTextPresent("Listings shown in RSS feed: this field is required") , 'JS Validation');
        $this->assertTrue( $this->isTextPresent("Latest listings shown: this field is required") , 'JS Validation');
        $this->assertTrue( $this->isTextPresent("The search page shows: this field is required") , 'JS Validation');
        $this->assertTrue( $this->isTextPresent("Email: this field is required") , 'JS Validation');


        $this->type("pageTitle"   ,"test title");
        $this->type("contactEmail","test email@.");
        $this->type("num_rss_items" , "a");
        $this->type("max_latest_items_at_home" , "b");
        $this->type("default_results_per_page" , "c");
        $this->click("//input[@type='submit']");
        sleep(4);

        $this->assertTrue( $this->isTextPresent("Listings shown in RSS feed: this field must only contain numeric characters") , 'JS Validation');
        $this->assertTrue( $this->isTextPresent("Latest listings shown: this field must only contain numeric characters") , 'JS Validation');
        $this->assertTrue( $this->isTextPresent("The search page shows: this field must only contain numeric characters") , 'JS Validation');
        $this->assertTrue( $this->isTextPresent("Invalid email address") , 'JS Validation');

        $this->type("pageTitle"   ,"New title web");
        $this->type("contactEmail","foo@bar.com");
        $this->type("pageDesc"    ,"Description web");
        $this->select("currency_admin", "label=EUR");
        $this->select("weekStart"     , "label=Saturday");
        $this->type("num_rss_items" , "61");
        $this->type("max_latest_items_at_home" , "21");
        $this->type("default_results_per_page" , "23");
        $this->click("m/d/Y");
        $this->click("H:i");
        $this->assertTrue( $this->getValue('enabled_attachment')==$pref['contact_attachment'] , 'Contact, check.');
        $this->click("enabled_attachment");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->getValue('pageTitle')     =="New title web" , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('contactEmail')  =="foo@bar.com"   , 'GeneralSettings, check.' );
        $this->assertTrue( $this->getValue('dateFormat')    =="m/d/Y"         , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('pageDesc')      =="Description web"  , 'GeneralSettings, check.');
//        $this->assertTrue( $this->getValue('language')      , 'en_US' );
        $this->assertTrue( $this->getValue('currency')      =='EUR'          , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('weekStart')     =='6'            , 'GeneralSettings, INT.');
        $this->assertTrue( $this->getValue('num_rss_items') =='61'           , 'GeneralSettings, INT.');
        $this->assertTrue( $this->getValue('max_latest_items_at_home')       =='21'  , 'GeneralSettings, INT.' );
        $this->assertTrue( $this->getValue('default_results_per_page')       =='23'  , 'GeneralSettings, INT.' );
        $this->assertTrue( $this->getValue('timeFormat')    =="H:i"          ,'GeneralSettings, check.');

        if( $pref['contact_attachment'] == 'on' ) {
            $this->assertTrue( $this->getValue('enabled_attachment')=='off', 'Contact, check.' );
        } else {
            $this->assertTrue( $this->getValue('enabled_attachment')=='on', 'Contact, check.' );
        }

        $this->click("//a[@id='settings_general']");
        $this->waitForPageToLoad("10000");
        $this->type("pageTitle"   , $pref['pageTitle']);
        $this->type("contactEmail", $pref['contactEmail']);
        $this->type("pageDesc"    , $pref['pageDesc']);
        $this->select("currency_admin", "label=" . $pref['currency'] );
        $this->select("weekStart"     , "value=" . $pref['weekStart'] );
        $this->type("num_rss_items" , $pref['num_rss_items'] );
        $this->type("max_latest_items_at_home" , $pref['max_latest_items_at_home'] );
        $this->type("default_results_per_page" , $pref['default_results_per_page'] );
        $this->click($pref['df']);
        $this->click($pref['tf']);
        $this->click("enabled_attachment");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->getValue('pageTitle')     ==$pref['pageTitle']      , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('contactEmail')  ==$pref['contactEmail']   , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('dateFormat')    ==$pref['df']             , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('pageDesc')      ==$pref['pageDesc']       , 'GeneralSettings, check.');
//        $this->assertTrue( $this->getValue('language')      , $pref['language']       , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('currency')      ==$pref['currency']       , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('weekStart')     ==$pref['weekStart']      , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('num_rss_items') ==$pref['num_rss_items']  , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('default_results_per_page') ==$pref['default_results_per_page']  , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('timeFormat')    ==$pref['tf']             , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('max_latest_items_at_home') ==$pref['max_latest_items_at_home']  , 'GeneralSettings, check.');
        $this->assertTrue( $this->getValue('enabled_attachment')==$pref['contact_attachment'], 'Contact, check.' );




        for($k=0;$k<20;$k++) {
            $custom_date = $this->_generateCustomDate();
            $this->click("//input[@id='df_custom']");
            $this->type("df_custom_text", $custom_date);
            $this->keyUp("df_custom_text", "a");
            $date = trim(date($custom_date));
            sleep(2);
            $this->assertTrue( $this->isTextPresent("Preview: ".$date) , "Custom date failed with this string : '".$custom_date."' check: '".date($custom_date)."'" );
        }

        osc_reset_preferences();
    }

    function testLocationsGEO()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_locations']");
        $this->waitForPageToLoad("10000");

        $this->click("xpath=//a[@id='b_new_country']");

        $this->type("country"     , "Andorra" );
        $this->type("c_country"   , "AD" );
        $this->type('c_manual'    , '0');

        $this->click("xpath=//button[contains(.,'Add country')]");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("regexp:has been added as a new country") , "Can't add new country" );

        // edit country
        $this->click("xpath=//div[@id='l_countries']/div[1]/div[1]/div/a[@class='edit']");
        $this->type("e_country"     , "Andorra_" );

        $this->click("xpath=//button[contains(.,'Edit country')]");
        //$this->click("xpath=//button[text()='Edit country']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("regexp:has been edited") , "Can't edit country name" );

        // delete country
        $this->click("xpath=//a[@class='close']");
        sleep(2);
        $this->click("xpath=//input[@id='location-delete-submit']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue( $this->isTextPresent("regexp:has been deleted") , "Can't delete Country" );
        osc_reset_preferences();
    }

    function testLastSearches()
    {

        $this->_login();
        sleep(2);
        $this->assertTrue(!$this->isTextPresent('Log in'), "Login oc-admin.");

        // TEST CHECKBOX TO ENABLE(DISABLE
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_searches']");
        $this->waitForPageToLoad("10000");

        $check = osc_get_preference('save_latest_searches');
        if($check == 1){ $check = 'on';} else { $check = 'off'; }

        $this->assertTrue($check==$this->getValue("save_latest_searches"), "Save or not latest searches.");

        $this->click("save_latest_searches");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");

        osc_reset_preferences();
        $check = osc_get_preference('save_latest_searches');
        if($check == 1){ $check = 'on';} else { $check = 'off'; }

        $this->assertTrue($check==$this->getValue("save_latest_searches"), "Save or not latest searches.");

        $this->click("save_latest_searches");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");

        osc_reset_preferences();
        $check = osc_get_preference('save_latest_searches');
        if($check == 1){ $check = 'on';} else { $check = 'off'; }

        $this->assertTrue($check==$this->getValue("save_latest_searches"), "Save or not latest searches.");


        $this->click("//input[@value='hour']");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue("hour"==$this->getValue("customPurge"), "Set to hour");

        $this->click("//input[@value='day']");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue("day"==$this->getValue("customPurge"), "Set to day");

        $this->click("//input[@value='week']");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue("week"==$this->getValue("customPurge"), "Set to week");

        $this->click("//input[@value='forever']");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue("forever"==$this->getValue("customPurge"), "Set to forever");

        $this->click("//input[@value='1000']");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue("1000"==$this->getValue("customPurge"), "Set to 1000");

        $this->click("//input[@value='custom']");
        $this->type("custom_queries", "");
        $this->keyUp("custom_queries", "a");
        $this->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue($this->isTextPresent("Custom number: this field cannot be left empty."), "Last Searches JS, update.");
        $this->click("//input[@value='custom']");
        $this->type("custom_queries", "123");
        $this->keyUp("custom_queries", "3");
        sleep(4);
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue("123"==$this->getValue("customPurge"), "Set to 123");

        $this->type("custom_queries", "");
        $this->keyUp("custom_queries", "a");
        sleep(4);
        $this->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue($this->isTextPresent("Custom number: this field cannot be left empty."), "Last Searches JS, update.");

        $this->click("//input[@value='week']");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue("week"==$this->getValue("customPurge"), "Set to week");


        osc_reset_preferences();
    }

    function testLocationsNEWForceError()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->waitForPageToLoad("4000");
        $this->click("//a[@id='settings_locations']");
        $this->waitForPageToLoad("10000");
        // add Country
        $this->click("xpath=//a[@id='b_new_country']");

        $this->type("country", "ikea");
        $this->type("c_country", "IK");

        $this->click("xpath=//button[contains(.,'Add country')]");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("regexp:has been added as a new country") , "Add new country" );

        // add country again

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_locations']");
        $this->waitForPageToLoad("10000");
        // add Country
        $this->click("xpath=//a[@id='b_new_country']");

        $this->type("country", "ikea");
        $this->type("c_country", "IK");

        $this->click("xpath=//button[contains(.,'Add country')]");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("regexp:already was in the database") , "Add country twice" );

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_locations']");
        $this->waitForPageToLoad("10000");
        // add Region
        $this->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']");
        $this->click("xpath=//a[@id='b_new_region']");

        $this->type("region", "Republica");

        $this->click("xpath=//button[contains(.,'Add region')]");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("regexp:has been added as a new region") , "Add new region" );

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_locations']");
        $this->waitForPageToLoad("10000");
        // add Region again
        $this->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']");
        $this->click("xpath=//a[@id='b_new_region']");

        $this->type("region", "Republica");

        $this->click("xpath=//button[contains(.,'Add region')]");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("regexp:already was in the database") , "Add region twice" );

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_locations']");
        $this->waitForPageToLoad("10000");
        // add City
        $this->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']");
        sleep(3);
        $this->click("xpath=//div[@id='i_regions']/div[1]/div/a[text()='View more »']");
        sleep(3);
        $this->click("xpath=//a[@id='b_new_city']");

        $this->type("city", "Mi casa");
        $this->click("xpath=//button[contains(.,'Add city')]");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("regexp:has been added as a new city") , "Add new city" );

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_locations']");
        $this->waitForPageToLoad("10000");
        // add City again
        $this->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']");
        sleep(3);
        $this->click("xpath=//div[@id='i_regions']/div[1]/div/a[text()='View more »']");
        sleep(3);
        $this->click("xpath=//a[@id='b_new_city']");

        $this->type("city", "Mi casa");
        $this->click("xpath=//button[contains(.,'Add city')]");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("regexp:already was in the database") , "Add city twice" );

        //test errors when edit countries, regions, cities

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_locations']");
        $this->waitForPageToLoad("10000");
        // add another City
        $this->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']");
        sleep(3);
        $this->click("xpath=//div[@id='i_regions']/div[1]/div/a[text()='View more »']");
        sleep(3);
        $this->click("xpath=//a[@id='b_new_city']");

        $this->type("city", "Mi casa_");
        $this->click("xpath=//button[contains(.,'Add city')]");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("regexp:has been added as a new city") , "Add new city" );

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_locations']");
        $this->waitForPageToLoad("10000");
        // edit the city and change the name to existing one
        $this->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']");
        sleep(3);
        $this->click("xpath=//div[@id='i_regions']/div[1]/div/a[text()='View more »']");
        sleep(3);
        $this->click("xpath=//div[@id='i_cities']/div/div/div/a[text()='Mi casa_']");

        $this->type("e_city", "Mi casa");
        $this->click("xpath=//button[contains(.,'Edit city')]");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("regexp:already was in the database") , "Change city name to existing one" );

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_locations']");
        $this->waitForPageToLoad("10000");
        // add another Region
        $this->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']");
        sleep(3);
        $this->click("xpath=//a[@id='b_new_region']");

        $this->type("region", "Republica_");

        $this->click("xpath=//button[contains(.,'Add region')]");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("regexp:has been added as a new region") , "Add new region" );

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_locations']");
        $this->waitForPageToLoad("10000");
        // edit the region and change the name to existing one
        $this->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']");
        sleep(3);
        $this->click("xpath=//div[@id='i_regions']/div/div/div/a[text()='Republica_']");
        $this->type("e_region", "Republica");

        $this->click("xpath=//button[contains(.,'Edit region')]");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("regexp:already was in the database") , "Change region name to existing one" );

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_locations']");
        $this->waitForPageToLoad("10000");
        // DELETE THE LOCATION
        $this->click("xpath=//a[@class='close'][1]");
        sleep(2);
        $this->click("xpath=//input[@id='location-delete-submit']");
        $this->waitForPageToLoad("10000");
        $this->assertTrue( $this->isTextPresent("regexp:has been deleted") , "Delete Country" );


        osc_reset_preferences();
    }

    function testCurrency()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_currencies']");
        $this->waitForPageToLoad("10000");

        $this->click("link=Add");

        $this->waitForPageToLoad("30000");

        $this->type("pk_c_code", "");
        $this->type("s_name", "");
        $this->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue( $this->isTextPresent("Currency code: this field is required") , "Add currency" );
        $this->assertTrue( $this->isTextPresent("Name: this field is required") , "Add currency" );

        $this->type("pk_c_code", "INR");
        $this->type("s_name", "Indian Rupee");
        $this->type("s_description", "Indian Rupee र");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Currency added") , "Add currency" );
        // edit
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_currencies']");
        $this->waitForPageToLoad("10000");

        $this->click("//table/tbody/tr/td[contains(.,'INR')]/a[text()='Edit']");
        $this->waitForPageToLoad("10000");

        $this->type("s_name", "");
        $this->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue( $this->isTextPresent("Name: this field is required") , "Add currency" );


        $this->type("s_name", "Indian_Rupee");
        $this->type("s_description", "Indian_Rupee र");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Currency updated") , "Edit currency" );
        // delete
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_currencies']");
        $this->waitForPageToLoad("10000");

        $this->click("//table/tbody/tr/td[contains(.,'INR')]/a[text()='Delete']");
        sleep(2);
        $this->click("//input[@id='currency-delete-submit']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue( $this->isTextPresent("One currency has been deleted") , "Delete currency" );
        $this->assertTrue( !$this->isTextPresent("Indian_Rupee") , "Delete currency" );


        // BULK DELETE
        $this->open( osc_admin_base_url(true) );
        $this->waitForPageToLoad("10000");
        $this->click("//a[@id='settings_currencies']");
        $this->waitForPageToLoad("10000");

        $this->click("link=Add");

        $this->waitForPageToLoad("30000");

        $this->type("pk_c_code", "");
        $this->type("s_name", "");
        $this->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue( $this->isTextPresent("Currency code: this field is required") , "Add currency" );
        $this->assertTrue( $this->isTextPresent("Name: this field is required") , "Add currency" );

        $this->type("pk_c_code", "INR");
        $this->type("s_name", "Indian Rupee");
        $this->type("s_description", "Indian Rupee र");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Currency added") , "Add currency" );

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_currencies']");
        $this->waitForPageToLoad("10000");

        $this->click("link=Add");

        $this->waitForPageToLoad("30000");

        $this->type("pk_c_code", "");
        $this->type("s_name", "");
        $this->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue( $this->isTextPresent("Currency code: this field is required") , "Add currency" );
        $this->assertTrue( $this->isTextPresent("Name: this field is required") , "Add currency" );

        $this->type("pk_c_code", "AUD");
        $this->type("s_name", "Australian dolar");
        $this->type("s_description", "Australian dolar");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Currency added") , "Add currency" );

        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_currencies']");
        $this->waitForPageToLoad("10000");

        $this->click("//table/tbody/tr/td/input[@value='INR']");
        $this->click("//table/tbody/tr/td/input[@value='AUD']");
        $this->select("bulk_actions", "label=Delete" );
        sleep(2);
        $this->click("//input[@type='submit']");
        sleep(2);
        $this->click("//a[@id='bulk-actions-submit']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue( $this->isTextPresent("2 currencies have been deleted") , "BULK delete currency" );

        osc_reset_preferences();
    }

    function testAddCurrencyTwice()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_currencies']");
        $this->waitForPageToLoad("10000");

        $this->click("link=Add");

        $this->waitForPageToLoad("30000");

        $this->type("pk_c_code", "INR");
        $this->type("s_name", "Indian Rupee");
        $this->type("s_description", "Indian Rupee र");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Currency added") , "Add currency" );

        // add the same currency again
        $this->open( osc_admin_base_url(true) );
        $this->waitForPageToLoad("4000");
        $this->click("//a[@id='settings_currencies']");
        $this->waitForPageToLoad("10000");

        $this->click("link=Add");

        $this->waitForPageToLoad("30000");

        $this->type("pk_c_code", "INR");
        $this->type("s_name", "Indian Rupee");
        $this->type("s_description", "Indian Rupee र");

        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("10000");

        $this->assertTrue( $this->isTextPresent("Currency couldn't be added") , "Add currency twice. ERROR" );

        // delete
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_currencies']");
        $this->waitForPageToLoad("10000");

        $this->click("//table/tbody/tr/td[contains(.,'INR')]/a[text()='Delete']");
        sleep(2);
        $this->click("//input[@id='currency-delete-submit']");
        $this->waitForPageToLoad("30000");

        $this->assertTrue( $this->isTextPresent("One currency has been deleted") , "Delete currency" );
        $this->assertTrue( !$this->isTextPresent("Indian_Rupee") , "Delete currency" );
        osc_reset_preferences();
    }

    // TODO PERMALINKS DO NOT SHOW ANY MESSAGE AT ALL (VALIDATION)
    function _testPermalinks()
    {
        $this->_login();
        $this->open( osc_admin_base_url(true) );
        $this->click("//a[@id='settings_permalinks']");
        $this->waitForPageToLoad("30000");
        $value = $this->getValue('rewrite_enabled');

        // If they were off, enable it
        if($value=='off') {
            $this->click("rewrite_enabled");
            $this->click("//input[@type='submit']");
            $this->waitForPageToLoad("30000");
            $this->assertTrue( $this->isTextPresent("Permalinks structure updated") , "Disable permalinks" );
        }

        $this->click("link=Show rules");
        sleep(3);

        $this->type("rewrite_item_url", "");
        $this->type("rewrite_page_url", "");
        $this->type("rewrite_cat_url", "");
        $this->type("rewrite_search_url", "");
        $this->type("rewrite_search_country", "");
        $this->type("rewrite_search_region", "");
        $this->type("rewrite_search_city", "");
        $this->type("rewrite_search_city_area", "");
        $this->type("rewrite_search_pattern", "");
        $this->type("rewrite_search_category", "");
        $this->type("rewrite_search_user", "");
        $this->type("rewrite_contact", "");
        $this->type("rewrite_feed", "");
        $this->type("rewrite_language", "");
        $this->type("rewrite_item_mark", "");
        $this->type("rewrite_item_send_friend", "");
        $this->type("rewrite_item_contact", "");
        $this->type("rewrite_item_new", "");
        $this->type("rewrite_item_activate", "");
        $this->type("rewrite_item_edit", "");
        $this->type("rewrite_item_delete", "");
        $this->type("rewrite_item_resource_delete", "");
        $this->type("rewrite_user_login", "");
        $this->type("rewrite_user_dashboard", "");
        $this->type("rewrite_user_logout", "");
        $this->type("rewrite_user_register", "");
        $this->type("rewrite_user_activate", "");
        $this->type("rewrite_user_activate_alert", "");
        $this->type("rewrite_user_profile", "");
        $this->type("rewrite_user_items", "");
        $this->type("rewrite_user_alerts", "");
        $this->type("rewrite_user_recover", "");
        $this->type("rewrite_user_forgot", "");
        $this->type("rewrite_user_change_password", "");
        $this->type("rewrite_user_change_email", "");
        $this->type("rewrite_user_change_email_confirm", "");
        $this->type("rewrite_user_change_username", "");
        $this->click("//input[@type='submit']");
        sleep(4);


        $this->assertTrue( $this->isTextPresent("All fields are required. 37 fields were not updated") , "Empty permalink" );


        $this->type("rewrite_item_url", "item/{ITEM_ID}/{ITEM_TITLE}");
        $this->type("rewrite_page_url", "page/{PAGE_SLUG}");
        $this->type("rewrite_cat_url", "{CATEGORIES}");
        $this->type("rewrite_search_url", "search");
        $this->type("rewrite_search_country", "country");
        $this->type("rewrite_search_region", "region");
        $this->type("rewrite_search_city", "city");
        $this->type("rewrite_search_city_area", "cityarea");
        $this->type("rewrite_search_pattern", "pattern");
        $this->type("rewrite_search_category", "category");
        $this->type("rewrite_search_user", "user");
        $this->type("rewrite_contact", "contact");
        $this->type("rewrite_feed", "feed");
        $this->type("rewrite_language", "language");
        $this->type("rewrite_item_mark", "item/mark");
        $this->type("rewrite_item_send_friend", "item/send-friend");
        $this->type("rewrite_item_contact", "item/contact");
        $this->type("rewrite_item_new", "item/new");
        $this->type("rewrite_item_activate", "item/activate");
        $this->type("rewrite_item_edit", "item/edit");
        $this->type("rewrite_item_delete", "item/delete");
        $this->type("rewrite_item_resource_delete", "resource/delete");
        $this->type("rewrite_user_login", "user/login");
        $this->type("rewrite_user_dashboard", "user/dashboard");
        $this->type("rewrite_user_logout", "user/logout");
        $this->type("rewrite_user_register", "user/register");
        $this->type("rewrite_user_activate", "user/activate");
        $this->type("rewrite_user_activate_alert", "alert/confirm");
        $this->type("rewrite_user_profile", "user/profile");
        $this->type("rewrite_user_items", "user/items");
        $this->type("rewrite_user_alerts", "user/alerts");
        $this->type("rewrite_user_recover", "user/recover");
        $this->type("rewrite_user_forgot", "user/forgot");
        $this->type("rewrite_user_change_password", "password/change");
        $this->type("rewrite_user_change_email", "email/change");
        $this->type("rewrite_user_change_email_confirm", "email/confirm");
        $this->type("rewrite_user_change_username", "username/change");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue( $this->isTextPresent("Permalinks structure updated") , "Disable permalinks" );

        // Disable at the end of the tests
        $this->click("rewrite_enabled");
        $this->click("//input[@type='submit']");
        $this->waitForPageToLoad("30000");
        $this->assertTrue( $this->isTextPresent("Friendly URLs successfully deactivated") , "Disable permalinks" );

        // return to previous state (before starting the tests)
        if($value=='on') {
            $this->click("rewrite_enabled");
            $this->click("//input[@type='submit']");
            $this->waitForPageToLoad("30000");
            $this->assertTrue( $this->isTextPresent("Friendly URLs successfully deactivated") , "Disable permalinks" );
        }
        osc_reset_preferences();
    }

    private function _getPreferencesGeneralSettings()
    {
        $pref = array();
        $pref['pageTitle']      = osc_get_preference('pageTitle');
        $pref['contactEmail']   = osc_get_preference('contactEmail');
        $pref['df']             = osc_get_preference('dateFormat');
        $pref['pageDesc']       = osc_get_preference('pageDesc');
        $pref['language']       = osc_get_preference('language');
        $pref['currency']       = osc_get_preference('currency');
        $pref['weekStart']      = osc_get_preference('weekStart');
        $pref['num_rss_items']  = osc_get_preference('num_rss_items');
        $pref['tf']             = osc_get_preference('timeFormat');
        $pref['default_results_per_page']  = osc_get_preference('defaultResultsPerPage@search');
        $pref['max_latest_items_at_home']  = osc_get_preference('maxLatestItems@home');
        $pref['contact_attachment'] = osc_get_preference('contact_attachment');
        if($pref['contact_attachment'] == 1){ $pref['contact_attachment'] = 'on';} else { $pref['contact_attachment'] = 'off'; }
        osc_reset_preferences();
        return $pref;
    }


    private function _generateCustomDate() {
        $str = ":_-/dDjlNSwzWFmMntLoyYaABgGhHieIOPTZ";
        $l = strlen($str);
        $date = '';
        for($i=0;$i<10;$i++) {
            $date .= substr($str, rand(0, $l), 1);
        }
        return $date;
    }


}
