<?php
//Initialises Configuration Object
require_once dirname(__FILE__)."/Config.class.php";
require_once dirname(__FILE__)."/file_get_contents.inc.php";
//Debug Purposes Only
$INIT_DEBUG = false;
if (!$INIT_DEBUG) error_reporting(0);

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
	if (!$config = unserialize(file_get_contents($confDat))) {
		if (!strstr($_SERVER['REQUEST_URI'],"/admin/setup")) {
			header("Location: ./admin/setup/");
			die();
		}
	}
	if (!is_writable($config->getNode("paths","offlineDir")."/conf.txt")) $config->setEditable(false);
	if (!isset($_PRINTDATA)) $_PRINTDATA = $config->getNode('server','debug');
}
?>