<?php
if(PHP_SAPI!='cli') { die('Only CLI'); };
require_once dirname(dirname(__FILE__)) . '/config.php';

if(!isset($_REQUEST['argv']) || !isset($_REQUEST['argc']) || $_REQUEST['argc']<3) {
    die('Missing params');
}

$repository = $_REQUEST['argv'][1];
$branch = $_REQUEST['argv'][2];

system('cd ' . TEST_SERVER_PATH . '; git fetch ' . $repository, $rv);
if($rv!=0) { echo 'GIT FETCH FAILED (' . $repository . ')'; exit; };

system('cd ' . TEST_SERVER_PATH . '; git reset --hard ' . $repository . '/' . $branch, $rv);
if($rv!=0) { echo 'GIT RESET FAILED (' . $repository . '/' . $branch . ')'; exit; };


