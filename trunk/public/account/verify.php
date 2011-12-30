<?php
$page_title = "Verification";
define("PAGE_TYPE","verifyAccount");
require_once dirname(__FILE__)."/../header.php";

ob_start();

$key = str_replace("'","''",$_GET['key']);
$result = $dbConn->query("SELECT action FROM `keys_action` WHERE key_id IN (SELECT id FROM `keys` WHERE `key`='$key') AND action LIKE 'ActivateAccount_%' LIMIT 1");

if ($dbConn->rows($result) == 0) {
	echo $config->getNode("messages","accountVerifyFail");
} else {
	$result = $dbConn->fetch($result);
	$userID = str_replace("ActivateAccount_","",$result['action']);
	$user = new User($userID);
	unset($result,$userID);
	$user->activate();
	$uname = $user->getUname();
	$dbConn->query("DELETE FROM `keys` WHERE `key`='$key'");
	echo $config->getNode("messages","accountVerifySuccess");
}

templateContent();

require_once dirname(__FILE__)."/../footer.php";
?>