<?php
require_once dirname(__FILE__)."/../header.php";
$initTime = $dbConn->time();
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

//Store last run time
$config->setNode("server","lastCron",time(),"Last Cron Run");

echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Scheduled Tasks Completed.</div>";
?>