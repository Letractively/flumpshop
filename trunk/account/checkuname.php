<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../preload.php";
$banned = array("Webmaster","Admin","Administrator","User","Test","Anonymous","TestUser");
$uname = str_replace("'","''",$_GET['username']);
if (array_search($uname,$banned) !== false) die(json_encode(false));
echo strval((bool)!$dbConn->rows($dbConn->query("SELECT id FROM login WHERE uname='".$uname."' LIMIT 1")));
?>