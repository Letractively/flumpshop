<?php
$USR_REQUIREMENT = "can_create_orders";
require_once "../../../../preload.php";

$term = str_replace("'","''",$_GET['term']);

$result = $dbConn->query("SELECT id,name FROM `products` WHERE (name LIKE '%".$term."%' OR id='".$term."' or sku LIKE '%".$term."%') AND active=1 LIMIT 8");

$array = array();

while ($row = $dbConn->fetch($result)) {
	$array[] = array($row['id'],$row['name']);
}

if (empty($array)) $array[] = array(0,"No results found");

echo json_encode($array);
?>