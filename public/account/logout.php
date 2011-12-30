<?php
require_once dirname(__FILE__)."/../preload.php";

$_SESSION['login']['active'] = false;
if (isset($_SESSION['adminAuth'])) unset($_SESSION['adminAuth']);
if (isset($_SESSION['acpusr'])) unset($_SESSION['acpusr']);

$url = $_SERVER['HTTP_REFERER'];

if (empty($url)) {
	die("This is a server-side endpoint. Please logout using the main site.");
}

$return = "loggedOut";

//Remove Previous Login Attempts
$url = str_replace(array("unknownUname&","&unknownUname","?unknownUname",
						 "invalidPass&","&invalidPass","?invalidPass",
						 "loginSuccess&","&loginSuccess","?loginSuccess"),"",$url);

if (strstr($url,"?")) {
	$url .= "&$return";
} else {
	$url .= "?$return";
}

header("Location: $url");
?>