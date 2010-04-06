<?php
//Initialises Configuration Object
require_once dirname(__FILE__)."/Config.class.php";
require_once dirname(__FILE__)."/file_get_contents.inc.php";
//Debug Purposes Only
$INIT_DEBUG = true;
if (!$INIT_DEBUG) error_reporting(0);
$SYSTEM_DEBUG = $INIT_DEBUG;

//Allow use without preloader
if (!function_exists("debug_message")) {
	function debug_message($str,$bool = false) {return;}
}
if (!function_exists("init_err")) {
	function init_err($str) {die($str);}
}

//Global Vars Across Site
if (!file_exists(dirname(__FILE__)."/../conf.txt")) {
	if (!stristr($_SERVER['REQUEST_URI'],"/admin/setup")) {
		header("Location: ./admin/setup");
		exit();
	}
} else {
	$confDat = file_get_contents(dirname(__FILE__)."/../conf.txt");
	if (file_exists($confDat) && !is_dir($confDat)) {
		$config = unserialize(file_get_contents($confDat));
		if (!is_object($config)) {
			init_err("Could not access configuration file.");
		}
		if (!is_writable($confDat)) $config->setEditable(false);
	} elseif (file_exists($confDat."/conf.txt")) { //ISSUE RESOLVED 8/1/10 - Keep for historical reasons (Why the hell not)
		$config = unserialize(file_get_contents($confDat."/conf.txt"));
		if (!is_object($config)) {
			init_err("Could not access configuration file.");
		}
		if (!is_writable($confDat."/conf.txt")) $config->setEditable(false);
	} else {
		if (!($config = unserialize($confDat))) {
			if (!strstr($_SERVER['REQUEST_URI'],"/admin/setup")) {
				header("Location: ./admin/setup/");
				die();
			}
		}
		if (!is_writable($config->getNode("paths","offlineDir")."/conf.txt")) $config->setEditable(false);
	}
	if (!isset($_PRINTDATA)) $_PRINTDATA = $config->getNode('server','debug');
	$SYSTEM_DEBUG = $_PRINTDATA;
}
?>