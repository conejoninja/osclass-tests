<?php
if(PHP_SAPI!='cli') { die('Only CLI'); };
if(file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/oc-load.php')) { require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/oc-load.php'; };
if(file_exists(dirname(dirname(dirname(__FILE__))) . '/Osclass/oc-load.php')) { require_once dirname(dirname(dirname(__FILE__))) . '/Osclass/oc-load.php'; };

if(!isset($_REQUEST['argv']) || !isset($_REQUEST['argc']) || $_REQUEST['argc']<2) {
    die('Missing params');
}

switch($_REQUEST['argv'][1]) {
    case 'in':
        break;
    case 'out':
        break;
}

