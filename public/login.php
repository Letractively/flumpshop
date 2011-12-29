<?php
require_once dirname(__FILE__)."/preload.php";

$uname = str_replace("'","''",$_POST['uname']);
$pass = md5($_POST['password']);

$result = $dbConn->query("SELECT * FROM `login` WHERE uname='$uname' LIMIT 1");

if ($dbConn->rows($result) == 0) {
	$return = "unknownUname";
} else {
	$result = $dbConn->fetch($result);
	if ($result['password'] != $pass) {
		$return = "invalidPass";
	} else {
		//Login Successful
		$_SESSION['login']['active'] = true;
		$_SESSION['login']['uname'] = $uname;
		$_SESSION['login']['id'] = $result['id'];
		$return = "loginSuccess";
	}
}

$url = $_SERVER['HTTP_REFERER'];

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