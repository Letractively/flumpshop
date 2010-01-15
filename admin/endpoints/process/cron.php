<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../preload.php";
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

echo "Scheduled Tasks Completed.";
?>