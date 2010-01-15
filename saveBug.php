<?php
require_once dirname(__FILE__)."/preload.php";

if (!isset($_POST['bugHeader']) or !isset($_POST['body'])) die();

$dbConn->query("INSERT INTO `bugs` (header,body) VALUES ('".str_replace("'","''",$_POST['bugHeader'])."','".str_replace("'","''",$_POST['body'])."')");

$url = $_SERVER['HTTP_REFERER'];

$return = "posted";
//Remove Previous Reports
$url = str_replace(array("posted&","&posted","?posted"),"",$url);

if (strstr($url,"?")) {
	$url .= "&$return";
} else {
	$url .= "?$return";
}

header("Location: ".$url);
?>