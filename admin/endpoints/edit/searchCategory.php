<?php
$USR_REQUIREMENT = "can_edit_categories";
require_once "../../../preload.php";

if (!isset($_SESSION['adminAuth']) or !$_SESSION['adminAuth']) exit;

$result = $dbConn->query("SELECT name FROM `category` WHERE name LIKE '%".$_GET['term']."%' OR id='".$_GET['term']."'");

$array = array();

while ($row = $dbConn->fetch($result)) {
	$array[] = $row['name'];
}

if (empty($array)) $array[] = "No results found";

echo json_encode($array);
?>