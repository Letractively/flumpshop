<?php
require_once dirname(__FILE__)."/../preload.php";

$uname = str_replace("'","''",$_POST['uname']);
$pass = md5($_POST['password']);

$result = $dbConn->query("SELECT * FROM `login` WHERE uname='$uname' AND active=1 LIMIT 1");

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
		if ($result['customer'] != 0) {
			$customer = new Customer($result['customer']);
			$_SESSION['locale'] = $customer->getCountry();
		} else {
			$_SESSION['locale'] = $config->getNode('site','country');
		}
		$return = "loginSuccess";
	}
}

$url = $_SERVER['HTTP_REFERER'];
if (stristr($url,"/account/createaccount.php")) {
	$url = $config->getNode("paths","root");
} 
if (empty($url)) {
	die("This is a server-side endpoint. Please use the main site to log in.");
}

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