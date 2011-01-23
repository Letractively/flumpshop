<?php
$USR_REQUIREMENT = "can_edit_products";
require_once "../../../preload.php";

$result = $dbConn->query("SELECT name FROM `products` WHERE name LIKE '%".$_GET['term']."%' OR id='".$_GET['term']."'");

$array = array();

while ($row = $dbConn->fetch($result)) {
	$array[] = $row['name'];
}

if (empty($array)) $array[] = "No results found";

echo json_encode($array);
?>