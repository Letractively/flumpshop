<?php
//Debug Purposes Only
$INIT_DEBUG = true;
if (!$INIT_DEBUG) error_reporting(0);

//Global Vars Across Site
if (!file_exists(dirname(__FILE__)."/../conf.txt")) {
	if (!stristr($_SERVER['REQUEST_URI'],"/admin/setup")) {
		header("Location: ./admin/setup");
		exit();
	}
} else {
	$confDat = file_get_contents(dirname(__FILE__)."/../conf.txt");
	if (!file_exists($confDat)) {
		//Configuration Data File is invisible!
		header('HTTP/1.1 500 Internal Server Error');
		echo '<h1>HTTP/1.1 500 Internal Server Error</h1>';
		echo '<p>An error has occurred that has prevent the server from handling requests.</p>',
				'<p>Error: Configuration file '.$confDat.' does not exist or is not accessible.</p>';
		exit;
	}
	if (!$GLOBALS['config'] = unserialize(file_get_contents($confDat))) {
		if (!strstr($_SERVER['REQUEST_URI'],"/admin/setup")) {
			header("Location: ./admin/setup/");
			die();
		}
	}
	if (!is_writable($config->getNode("paths","offlineDir")."/conf.txt")) $config->setEditable(false);
	if (!isset($_PRINTDATA)) $_PRINTDATA = $config->getNode('server','debug');
}