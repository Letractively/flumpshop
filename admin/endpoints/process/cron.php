<?php
$auth = false;
require_once dirname(__FILE__)."/../header.php";
$initTime = $dbConn->time();

//Clear Item Holds
$result = $dbConn->query("SELECT * FROM `reserve` WHERE expire<='".$initTime."'");

while ($row = $dbConn->fetch($result)) {
	$quantity = $row['quantity'];
	$item = $row['item'];
	$dbConn->query("UPDATE `products` SET stock=stock+$quantity WHERE id=$item LIMIT 1");
}

$dbConn->query("DELETE FROM `reserve` WHERE expire<='".$initTime."'");

$result = $dbConn->query("SELECT expiryAction FROM `keys` WHERE expiry<='".$initTime."'");
while ($row = $dbConn->fetch($result)) {
	eval(base64_decode($row['expiryAction']));
}

//Scheduled Backup
if ($config->getNode("server","lastBackup") < $initTime-($config->getNode('server','backupFreq')*3600) and $config->getNode('server','backupFreq') >= 1) {
	$storeExport = $config->getNode("paths","offlineDir")."/backup/backup-".date("d-m-y_His").".fml";
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-info'></span>Running backup - $storeExport</div>";
	include dirname(__FILE__)."/doExport.php";
	$config->setNode("server","lastBackup",time(),"Last Scheduled Backup");
}

//Store last run time
$config->setNode("server","lastCron",time(),"Last Cron Run");

echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Scheduled Tasks Completed.</div>";

$_GET['frame'] = "main";
include dirname(__FILE__)."/../../index.php";
?>