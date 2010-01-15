<?php
require_once dirname(__FILE__)."/../../../preload.php";

//Set all countries as disabled (unchecked boxes don't get submitted)
$dbConn->query("UPDATE `country` SET supported=0");

$query = "UPDATE `country` SET `supported`=1 WHERE false";
foreach (array_keys($_POST) as $country) {
	$query .= " OR `iso`='$country'";
}

$dbConn->query($query);
?>
Saved.