<?php
require_once dirname(__FILE__)."/../../../preload.php";
$initTime = $dbConn->time();

//Clear Item Holds
$result = $dbConn->query("SELECT item,quantity FROM `reserve` WHERE expire<='".$initTime."'");

while ($row = $dbConn->fetch($result)) {
	$quantity = $row['quantity'];
	$item = $row['item'];
	$dbConn->query("UPDATE `products` SET stock=stock+$quantity WHERE id=$item LIMIT 1");
}

$dbConn->query("DELETE FROM `reserve` WHERE expire<='".$initTime."'");

/**
 * @todo Fix this
 * $result = $dbConn->query("SELECT expiryAction FROM `keys` WHERE expiry<='".$initTime."'");
while ($row = $dbConn->fetch($result)) {
	$dbConn->query(($row['expiryAction']));
}
$result = $dbConn->query("DELETE FROM `keys` WHERE expiry<='".$initTime."'");
 */

//Scheduled Backup
if ($config->getNode("server","lastBackup") < $initTime-($config->getNode('server','backupFreq')*3600) and $config->getNode('server','backupFreq') > 0) {
	$config->setNode("server","lastBackup",time(),"Last Scheduled Backup");
	$storeExport = $config->getNode("paths","offlineDir")."/backup/backup-".date("d-m-y_His").".xml";
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-info'></span>Running backup - $storeExport</div>";
	require_once dirname(__FILE__)."/../data/export.php";
}

//Store last run time
$config->setNode("server","lastCron",time(),"Last Cron Run");

echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Scheduled Tasks Completed.</div>";
?>