<?php require_once dirname(__FILE__)."/../header.inc.php";
if (isset($_SESSION['config'])) unset($_SESSION['config']);
?><h1>Compatibility Checker</h1>
<p>Below, I've listed anything about your current server environment that I think should be bought to your attention. If you see a big red stripy box of doom, then it means that something is really wrong, and that you have to fix it before you can carry on. After that, click 'Customise' to do just that to your setup experience.</p><?php
//Analyse System Configuratiion
$success = array();
$fail = array();
$warn = array();
/*Root directory permissions*/
if (!is_writable(dirname(__FILE__)."/../../../")) {
		$fail[] = "I don't have access to the {siteroot} directory.";
}
/*PHP Version*/
if (PHP_VERSION < "4.4.9") {
		$fail[] = "PHP Version 4.4.9 or newer is required me to work. Please make sure the server has this installed before continuing";
} else {
	if (!PHP_VERSION >= "5.3.0") {
		$warn[] = "PHP v".PHP_VERSION." is supported, but it is recommended that you have version 5.3.0 or higher.";
	}
}
/*MySQLi Extension*/
if (!extension_loaded("mysqli")) {
	$warn[] = "The MySQLi extension is not installed. This extension is required for MySQL database access";
	$_SESSION['additions']['mysqli'] = false;
} else {
	$success[] = "The MySQLi extension is installed and loaded - You can use the MySQL database type";
	$_SESSION['additions']['mysqli'] = true;
}
/*Curl Extension*/
if (!extension_loaded("curl")) {
	$warn[] = "The CURL extension is not installed. This extension is required for the PayPal API";
	$_SESSION['additions']['curl'] = false;
} else {
	$success[] = "The CURL extension is installed and loaded - You can use the PayPal API for payment processing";
	$_SESSION['additions']['curl'] = true;
}
/*SimpleXML Extension*/
if (!extension_loaded("simplexml")) {
	if (!function_exists("dl") or !dl("simplexml")) {
		$warn[] = "The SimpleXML extension is not installed. Database logs will be disabled.";
		$_SESSION['additions']['sxml'] = false;
	} else {
		$_SESSION['additions']['sxml'] = true;
	}
} else {
	$success[] = "The SimpleXML extension is installed and loaded - Detailed database logs are supported";
	$_SESSION['additions']['sxml'] = true;
}
/*GD Extension*/
if (!extension_loaded("gd")) {
	$fail[] = "The GD extension is not installed. This extension is required for image manipulation";
}
/*Fileinfo Extension*/
if (!extension_loaded("fileinfo")) {
	if (!function_exists("dl") or !dl("fileinfo")) {
		if (PHP_VERSION >= "5.3.0") {
			$fail[] = "The Fileinfo extension is not installed. This extension is required for general file purposes";
		} else {
			$warn[] = "The Fileinfo extension is not installed. Some functionality may be unavailable. It is required if you upgrade to PHP 5.3 later.";
		}
	}
}
/*SQLite2 Extension*/
if (!extension_loaded("sqlite")) {
		$warn[] = "The SQLite extension is not installed. This extension is required for SQLite Database Support";
		$_SESSION['additions']['sqlite'] = false;
} else {
		$success[] = "The SQLite extension is installed and loaded - You can use the SQLite database mode";
		$_SESSION['additions']['sqlite'] = true;
}
if (!sizeof($fail) == 0) {
	echo "<div class='ui-widget ui-state-error'><div class='ui-widget-header ui-state-error'>Flumpshop needs the following resolved before it can continue</div><div class='ui-widget-content ui-state-error'>";
	foreach ($fail as $failure) {
			echo $failure."<br />";
	}
	echo "</div></div><br />";
}
if (!sizeof($warn) == 0) {
	echo "<div class='ui-widget ui-state-highlight'><div class='ui-widget-header'>Configuring these will enable advanced features</div><div class='ui-widget-content'>";
	foreach ($warn as $win) {
			echo $win."<br />";
	}
	echo "</div></div><br />";
}
if (!sizeof($success) == 0) {
	echo "<div class='ui-widget ui-state-default'><div class='ui-widget-header'>The following allow you to use additional features</div><div class='ui-widget-content'>";
	foreach ($success as $win) {
			echo $win."<br />";
	}
	echo "</div></div><br />";
}
if (!sizeof($fail) == 0) {
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-alert'></span>I need the above issue(s) fixed before I can continue! <a href='javascript:history.go(0);'>Try Again</a></div>";
} else {
	echo "<a onclick=\"parent.leftFrame.window.location='../?frame=leftFrame&p=1.3';\" href='./customise.php'><div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Customise your setup experience</div></a>";
}
require_once dirname(__FILE__)."/../footer.inc.php";
?>