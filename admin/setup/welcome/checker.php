<?php require_once dirname(__FILE__)."/../header.inc.php";
?><h1>Compatibility Checker</h1>
<p>Below, I've listed anything about your current server environment that I think should be bought to your attention. If you see a big red stripy box of doom, then it means that something is really wrong, and that you have to fix it before you can carry on. After that, click 'Customise' to do just that to your setup experience.</p><?php
//Analyse System Configuratiion
$success = array();
$fail = array();
$warn = array();
/*Log directory permissions*/
if (!is_writable(dirname(__FILE__)."/../../logs/")) {
		$warn[] = "I don't have access to the {siteroot}/admin/logs directory. The use of this is deprecated, but it's best you keep it open, just in case.";
}
/*Root directory permissions*/
if (!is_writable(dirname(__FILE__)."/../../../")) {
		$fail[] = "I don't have access to the {siteroot} directory. However, it is possible that ";
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
	$_SERVER['additions']['mysqli'] = false;
} else {
	$success[] = "The MySQLi extension is installed and loaded - You can use the MySQL database type";
	$_SERVER['additions']['mysqli'] = false;
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
	if (!dl("simplexml")) {
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
	if (!dl("fileinfo")) {
		if (PHP_VERSION >= "5.3.0") {
			$fail[] = "The Fileinfo extension is not installed. This extension is required for general file purposes";
		} else {
			$warn[] = "The Fileinfo extension is not installed. Some functionality may be unavailable. It is required if you upgrade to PHP 5.3 later.";
		}
	}
}
/*SQLite3 Extension*/
if (!extension_loaded("sqlite")) {
		$warn[] = "The SQLite extension is not installed. This extension is required for SQLite Database Support";
} else {
		$success[] = "The SQLite extension is installed and loaded - You can use the SQLite database mode";
}
if (!sizeof($fail) == 0) {
	echo "<div class='ui-widget ui-state-error'><div class='ui-widget-header ui-state-error'>Flumpshop needs the following resolved before it can continue</div><div class='ui-widget-content ui-state-error'>";
	foreach ($fail as $failure) {
			echo $failure."<br />";
	}
	echo "</div></div><br />";
}
if (!sizeof($warn) == 0) {
	echo "<div class='ui-widget ui-state-highlight'><div class='ui-widget-header'>Configuring these will enable advanced featured</div><div class='ui-widget-content'>";
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
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-alert'></span>I need the above issue(s) fixed before I can continue!</div>";
} else {
	echo "<a onclick=\"parent.leftFrame.window.location='../?frame=leftFrame&p=1.3';\" href='./customise.php'><div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Customise your setup experience</div></a>";
}
?>