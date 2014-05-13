<?php

require_once dirname(__FILE__) . '/OsclassTest.php';

class OsclassTestInstaller extends OsclassTest
{

    public function clean()
    {
        // REMOVE config.php file
        @unlink( TEST_SERVER_PATH . 'config.php' );
         // DROP DATABASE
        $mysqli = new mysqli(TEST_DB_HOST, TEST_DB_USER, TEST_DB_PASS);
        $mysqli->query("DROP DATABASE " . TEST_DB_NAME);
    }

    public function prepareTest()
    {
        @osc_test_copy(TEST_ASSETS_PATH . 'es_ES/', TEST_SERVER_PATH . 'oc-content/languages/');
    }


    public function _ocload() {
        define( 'ABS_PATH', TEST_SERVER_PATH);
        define('LIB_PATH', TEST_SERVER_PATH . 'oc-includes/');
        define('CONTENT_PATH', TEST_SERVER_PATH . 'oc-content/');
        define('THEMES_PATH', CONTENT_PATH . 'themes/');
        define('PLUGINS_PATH', CONTENT_PATH . 'plugins/');
        define('TRANSLATIONS_PATH', CONTENT_PATH . 'languages/');


        require_once TEST_SERVER_PATH . 'config.php';

        require_once LIB_PATH . 'osclass/db.php';
        require_once LIB_PATH . 'osclass/Logger/LogDatabase.php';
        require_once LIB_PATH . 'osclass/classes/database/DBConnectionClass.php';
        require_once LIB_PATH . 'osclass/classes/database/DBCommandClass.php';
        require_once LIB_PATH . 'osclass/classes/database/DBRecordsetClass.php';
        require_once LIB_PATH . 'osclass/classes/database/DAO.php';
        require_once LIB_PATH . 'osclass/model/SiteInfo.php';
        require_once LIB_PATH . 'osclass/helpers/hDatabaseInfo.php';
        require_once LIB_PATH . 'osclass/model/Preference.php';
        require_once LIB_PATH . 'osclass/helpers/hPreference.php';

        require_once LIB_PATH . 'osclass/helpers/hDefines.php';
        require_once LIB_PATH . 'osclass/helpers/hLocale.php';
        require_once LIB_PATH . 'osclass/helpers/hMessages.php';
        require_once LIB_PATH . 'osclass/helpers/hUsers.php';
        require_once LIB_PATH . 'osclass/helpers/hItems.php';
        require_once LIB_PATH . 'osclass/helpers/hSearch.php';
        require_once LIB_PATH . 'osclass/helpers/hUtils.php';

        require_once LIB_PATH . 'osclass/helpers/hCategories.php';
        require_once LIB_PATH . 'osclass/helpers/hTranslations.php';
        require_once LIB_PATH . 'osclass/helpers/hSecurity.php';
        require_once LIB_PATH . 'osclass/helpers/hSanitize.php';
        require_once LIB_PATH . 'osclass/helpers/hValidate.php';
        require_once LIB_PATH . 'osclass/helpers/hPage.php';
        require_once LIB_PATH . 'osclass/helpers/hPagination.php';
        require_once LIB_PATH . 'osclass/helpers/hPremium.php';
        require_once LIB_PATH . 'osclass/helpers/hTheme.php';
        require_once LIB_PATH . 'osclass/helpers/hLocation.php';
        require_once LIB_PATH . 'osclass/core/Params.php';
        require_once LIB_PATH . 'osclass/core/Cookie.php';
        require_once LIB_PATH . 'osclass/core/Session.php';
        require_once LIB_PATH . 'osclass/core/View.php';
        require_once LIB_PATH . 'osclass/core/BaseModel.php';
        require_once LIB_PATH . 'osclass/core/AdminBaseModel.php';
        require_once LIB_PATH . 'osclass/core/SecBaseModel.php';
        require_once LIB_PATH . 'osclass/core/WebSecBaseModel.php';
        require_once LIB_PATH . 'osclass/core/AdminSecBaseModel.php';
        require_once LIB_PATH . 'osclass/core/Translation.php';

        require_once LIB_PATH . 'osclass/Themes.php';
        require_once LIB_PATH . 'osclass/AdminThemes.php';
        require_once LIB_PATH . 'osclass/WebThemes.php';
        require_once LIB_PATH . 'osclass/compatibility.php';
        require_once LIB_PATH . 'osclass/utils.php';
        require_once LIB_PATH . 'osclass/formatting.php';
        require_once LIB_PATH . 'osclass/locales.php';
        require_once LIB_PATH . 'osclass/classes/Plugins.php';
        require_once LIB_PATH . 'osclass/helpers/hPlugins.php';
        require_once LIB_PATH . 'osclass/ItemActions.php';
        require_once LIB_PATH . 'osclass/emails.php';
        require_once LIB_PATH . 'osclass/model/Admin.php';
        require_once LIB_PATH . 'osclass/model/Alerts.php';
        require_once LIB_PATH . 'osclass/model/AlertsStats.php';
        require_once LIB_PATH . 'osclass/model/Cron.php';
        require_once LIB_PATH . 'osclass/model/Category.php';
        require_once LIB_PATH . 'osclass/model/CategoryStats.php';
        require_once LIB_PATH . 'osclass/model/City.php';
        require_once LIB_PATH . 'osclass/model/CityArea.php';
        require_once LIB_PATH . 'osclass/model/Country.php';
        require_once LIB_PATH . 'osclass/model/Currency.php';
        require_once LIB_PATH . 'osclass/model/OSCLocale.php';
        require_once LIB_PATH . 'osclass/model/Item.php';
        require_once LIB_PATH . 'osclass/model/ItemComment.php';
        require_once LIB_PATH . 'osclass/model/ItemResource.php';
        require_once LIB_PATH . 'osclass/model/ItemStats.php';
        require_once LIB_PATH . 'osclass/model/Page.php';
        require_once LIB_PATH . 'osclass/model/PluginCategory.php';
        require_once LIB_PATH . 'osclass/model/Region.php';
        require_once LIB_PATH . 'osclass/model/User.php';
        require_once LIB_PATH . 'osclass/model/UserEmailTmp.php';
        require_once LIB_PATH . 'osclass/model/ItemLocation.php';
        require_once LIB_PATH . 'osclass/model/Widget.php';
        require_once LIB_PATH . 'osclass/model/Search.php';
        require_once LIB_PATH . 'osclass/model/LatestSearches.php';
        require_once LIB_PATH . 'osclass/model/Field.php';
        require_once LIB_PATH . 'osclass/model/Log.php';
        require_once LIB_PATH . 'osclass/model/CountryStats.php';
        require_once LIB_PATH . 'osclass/model/RegionStats.php';
        require_once LIB_PATH . 'osclass/model/CityStats.php';
        require_once LIB_PATH . 'osclass/model/BanRule.php';

        require_once LIB_PATH . 'osclass/model/LocationsTmp.php';

        require_once LIB_PATH . 'osclass/classes/Cache.php';
        require_once LIB_PATH . 'osclass/classes/ImageResizer.php';
        require_once LIB_PATH . 'osclass/classes/RSSFeed.php';
        require_once LIB_PATH . 'osclass/classes/Sitemap.php';
        require_once LIB_PATH . 'osclass/classes/Pagination.php';
        require_once LIB_PATH . 'osclass/classes/Rewrite.php';
        require_once LIB_PATH . 'osclass/classes/Stats.php';
        require_once LIB_PATH . 'osclass/classes/AdminMenu.php';
        require_once LIB_PATH . 'osclass/classes/datatables/DataTable.php';
        require_once LIB_PATH . 'osclass/classes/AdminToolbar.php';
        require_once LIB_PATH . 'osclass/classes/Breadcrumb.php';
        require_once LIB_PATH . 'osclass/classes/EmailVariables.php';
        require_once LIB_PATH . 'osclass/alerts.php';

        require_once LIB_PATH . 'osclass/classes/Dependencies.php';
        require_once LIB_PATH . 'osclass/classes/Scripts.php';
        require_once LIB_PATH . 'osclass/classes/Styles.php';

        require_once LIB_PATH . 'osclass/frm/Form.form.class.php';
        require_once LIB_PATH . 'osclass/frm/Page.form.class.php';
        require_once LIB_PATH . 'osclass/frm/Category.form.class.php';
        require_once LIB_PATH . 'osclass/frm/Item.form.class.php';
        require_once LIB_PATH . 'osclass/frm/Contact.form.class.php';
        require_once LIB_PATH . 'osclass/frm/Comment.form.class.php';
        require_once LIB_PATH . 'osclass/frm/User.form.class.php';
        require_once LIB_PATH . 'osclass/frm/Language.form.class.php';
        require_once LIB_PATH . 'osclass/frm/SendFriend.form.class.php';
        require_once LIB_PATH . 'osclass/frm/Alert.form.class.php';
        require_once LIB_PATH . 'osclass/frm/Field.form.class.php';
        require_once LIB_PATH . 'osclass/frm/Admin.form.class.php';
        require_once LIB_PATH . 'osclass/frm/ManageItems.form.class.php';
        require_once LIB_PATH . 'osclass/frm/BanRule.form.class.php';

        require_once LIB_PATH . 'osclass/functions.php';
        require_once LIB_PATH . 'osclass/helpers/hAdminMenu.php';

    }



}


function osc_test_copy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755)) {
    $result =true;
    if (is_file($source)) {
        if ($dest[strlen($dest)-1]=='/') {
            if (!file_exists($dest)) {
                cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
            }
            $__dest=$dest."/".basename($source);
        } else {
            $__dest=$dest;
        }
        if(function_exists('copy')) {
            $result = @copy($source, $__dest);
        } else {
            $result=osc_test_copyemz($source, $__dest);
        }
        @chmod($__dest,$options['filePermission']);

    } elseif(is_dir($source)) {
        if ($dest[strlen($dest)-1]=='/') {
            if ($source[strlen($source)-1]=='/') {
                //Copy only contents
            } else {
                //Change parent itself and its contents
                $dest=$dest.basename($source);
                @mkdir($dest);
                @chmod($dest,$options['filePermission']);
            }
        } else {
            if ($source[strlen($source)-1]=='/') {
                //Copy parent directory with new name and all its content
                @mkdir($dest,$options['folderPermission']);
                @chmod($dest,$options['filePermission']);
            } else {
                //Copy parent directory with new name and all its content
                @mkdir($dest,$options['folderPermission']);
                @chmod($dest,$options['filePermission']);
            }
        }

        $dirHandle=opendir($source);
        $result = true;
        while($file=readdir($dirHandle)) {
            if($file!="." && $file!="..") {
                if(!is_dir($source."/".$file)) {
                    $__dest=$dest."/".$file;
                } else {
                    $__dest=$dest."/".$file;
                }
                //echo "$source/$file ||| $__dest<br />";
                $data = osc_test_copy($source."/".$file, $__dest, $options);
                if($data==false) {
                    $result = false;
                }
            }
        }
        closedir($dirHandle);

    } else {
        $result=true;
    }
    return $result;
}



function osc_test_copyemz($file1,$file2){
    $contentx =@file_get_contents($file1);
    $openedfile = fopen($file2, "w");
    fwrite($openedfile, $contentx);
    fclose($openedfile);
    if ($contentx === FALSE) {
        $status=false;
    } else {
        $status=true;
    }

    return $status;
}


