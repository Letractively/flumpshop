<?php
date_default_timezone_set("Europe/London");
if (stristr($_SERVER['REQUEST_URI'],"admin/setup")) $_SETUP = true; else $_SETUP = false;
if (!isset($ajaxProvider)) $ajaxProvider = false;

function init_err($msg) {
	global $config, $_SETUP, $INIT_DEBUG;
	if (!$_SETUP && !$INIT_DEBUG) {
		if (is_object($config)) header("Location: ".$config->getNode('paths','root')."/errors/500.php?err=".base64_encode($msg));
		else header("Location: ./errors/500.php?err=".base64_encode($msg));
	}
	die($msg);
}
require_once(dirname(__FILE__)."/includes/Config.class.php");
require_once(dirname(__FILE__)."/includes/vars.inc.php");
error_reporting(E_ALL);
if (isset($config)) {
	if($config->getNode('logs','errors')) $errLog = fopen($config->getNode('paths','logDir')."/errors.log","a+");
	if($config->getNode('server','debug')) $debugLog = fopen($config->getNode('paths','logDir')."/debug.log","a+");
}

function sys_error($level,$msg,$file,$line) {
	global $errLog, $_PRINTDATA, $ajaxProvider;
	if ($_PRINTDATA && !$ajaxProvider) echo "<div class='ui-state-error ui-corner-all'><span class='ui-icon ui-icon-script'></span>$msg ($file:$line)</div>";
	if (isset($errLog)) fwrite($errLog,"(".date("d/m/y H:i:s").") $msg [$file:$line]\r\n");
	return true;
}

set_error_handler("sys_error");

function debug_message($msg,$check = false) {
	global $_PRINTDATA, $debugLog, $ajaxProvider;
	if ($_PRINTDATA && !$ajaxProvider) {
		if ($check) $class = "ui-icon-circle-check"; else $class="ui-icon-script";
		echo "<div class='ui-state-highlight'><span class='ui-icon $class'></span>$msg</div>";
		if (is_resource($debugLog)) fwrite($debugLog,$msg."\r\n");
	}
}

if (!isset($_SESSION)) {
	session_start();
	debug_message("Session Initialized");
}

if ($_SETUP == false && $config->getNode('site','enabled') != true && !strstr($_SERVER['REQUEST_URI'],"/admin/")) {
	require_once dirname(__FILE__)."/errors/maintenance.php";
	die();
}

//Load Classes
require_once(dirname(__FILE__)."/includes/Item.class.php");
debug_message("Item Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Basket.class.php");
debug_message("Basket Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Customer.class.php");
debug_message("Customer Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/User.class.php");
debug_message("User Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Database.class.php");
debug_message("Database Class Definitions Loaded.");
require_once(dirname(__FILE__)."/includes/Category.class.php");
debug_message("Category Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Stats.class.php");
debug_message("Stats Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Order.class.php");
debug_message("Order Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Delivery.class.php");
debug_message("Delivery Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/News.class.php");
debug_message("News Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Reserve.class.php");
debug_message("Reserve Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Session.class.php");
debug_message("Session Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Techhelp.class.php");
debug_message("Techhelp Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Mail.class.php");
debug_message("Mail Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Paginator.class.php");
debug_message("Paginator Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Keycodes.class.php");
debug_message("Keycodes Class Definition Loaded.");

$stats = new Stats();

//Check PHP Version
if (PHP_VERSION < "4.4.9") {
	if (!$_SETUP) init_err("Unsupported PHP Version. (v".PHP_VERSION.")");
} else {
	debug_message("PHP Version Supported (v".PHP_VERSION.")",true);
}

//Check CURL Installed
if (extension_loaded("curl")) {
	debug_message("Curl Extension Installed",true);
} else {
	if (!$_SETUP) init_err("Curl Extension NOT Loaded.");
}
//Check SimpleXML Installed
if (extension_loaded("simplexml")) {
	debug_message("SimpleXML Extension Installed",true);
	if (isset($config)) $config->setNode("temp","simplexml",true);
} else {
	if (!@dl("simplexml")) {
		debug_message("SimpleXML Extension not Available. Database logs disabled.");
		if (isset($config)) $config->setNode("temp","simplexml",false);
	}
}
//Check GD Installed
if (extension_loaded("gd")) {
	debug_message("GD Extension Installed",true);	
} else {
	if (!$_SETUP) init_err("GD Extension NOT Loaded.");
}
//Check Fileinfo Installed
if (extension_loaded("fileinfo")) {
	debug_message("Fileinfo Extension Installed",true);
	if (isset($config)) $config->setNode("temp","fileinfo",true);
} else {
	if (!@dl("fileinfo")) {
		if (PHP_VERSION >= "5.3.0") {
			if (!$_SETUP) init_err("Fileinfo Extension NOT Loaded");
		} else {
			debug_message("Fileinfo Extension not Available. Some features may be unavailable.");
			if (isset($config)) $config->setNode("temp","fileinfo",false);
		}
	}
}

if ($_SETUP == false) {
	//Connect to Database
	$dbConn = db_factory();
	
	if (!$dbConn->isConnected()) {
		init_err("Database Connection Failed.");
	}
	debug_message("Database Connection Established",true);
	#$dbConn->multi_query(file_get_contents(__DIR__."/admin/install.sql")); //Debug for SQLite (Rebuilds DB)
	$session = $dbConn->query("SELECT * FROM `sessions` WHERE session_id='".session_id()."' LIMIT 1");
	if ($session === false && $_PRINTDATA) {
		trigger_error($dbConn->error());
	}
	
	if ($dbConn->rows($session) == 0) {
		//Build Session
		debug_message("Generating new Session Data");
		//Web Crawler Exception
		if (array_search($_SERVER['HTTP_USER_AGENT'],explode($config->getNode('server','crawlerAgents'),"|"))) {
			$config->setNode('temp','crawler',true);
			$basket = new Basket(0);
			$basket->lock();
			debug_message("Basket Disabled for Crawlers");
		} else {
			$config->setNode('temp','crawler',false);
			$dbConn->query("INSERT INTO `basket` (obj) VALUES ('')");
			$basketid = $dbConn->insert_id();
			$basket = new Basket($basketid);
			debug_message("Created basket #$basketid",true);
		}
		if (!isset($_SERVER['REMOTE_ADDR'])) $ip = "127.0.0.1"; else $ip = $_SERVER['REMOTE_ADDR'];
		$dbConn->query("INSERT INTO `sessions` (session_id,basket,ip_addr) VALUES ('".session_id()."',$basketid,INET_ATON('".$ip."'))");
		debug_message("Remote IP: $ip");
	} else {
		if (array_search($_SERVER['HTTP_USER_AGENT'],explode($config->getNode('server','crawlerAgents'),"|"))) {
			$basket = new Basket(0);
			$basket->lock();
			$config->setNode('temp','crawler',true);
		} else {
			$dbConn->query("UPDATE `sessions` SET active='".$dbConn->time()."' WHERE session_id='".session_id()."'");
			$session = $dbConn->fetch($session);
			$basket = $dbConn->query("SELECT * FROM `basket` WHERE id='".$session['basket']."' LIMIT 1");
			$basket = $dbConn->fetch($basket);
			$basket = unserialize(base64_decode($basket['obj']));
			if (!is_object($basket)) {
				trigger_error("Fatal Error: Basket Corrupted. Aborting.");
				die();
			}
			$basket->restore();
			$config->setNode('temp','crawler',false);
		}
	}
	
	debug_message("Loaded Session #".session_id(),true);
}

//Locale
if (isset($_SESSION['locale']) && !$_SETUP) $config->setNode("temp","country",$_SESSION['locale']);

//Admin Functions
//Validation
function get_valid_class($tree,$node) {
	$class = "";
	switch ($tree) {
		case "paths":
			switch ($node) {
				case "root":
					$class .= "url ";
				case "path":
					$class .= "required ";
				break;
			}
			break;
		
	}
	return $class;
}

//Used for CMS pages
function placeholders($string) {
	global $config;
	$string = preg_replace_callback("/\[\[([a-z]*)?\]\]/i","placeholder_callback",$string);
	return $string;
}

function placeholder_callback($args) {
	global $config;
	return $config->getNode('messages',$args[1]);
}
?>