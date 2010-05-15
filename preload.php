<?php
//Some headers sent later & gzip compression
ob_start("ob_gzhandler");
//Timer
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

header("Content-Type: text/html;charset=UTF-8");

$time_start = microtime_float();
ini_set("date.timezone","Europe/London");

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

if (isset($config)) {
	if($config->getNode('logs','errors')) $errLog = fopen($config->getNode('paths','logDir')."/errors.log","a+");
	if($config->getNode('server','debug')) $debugLog = fopen($config->getNode('paths','logDir')."/debug.log","a+");
}

function sys_error($level,$msg,$file,$line) {
	global $errLog, $_PRINTDATA, $ajaxProvider;
	if (!stristr($msg,"Unable to load dynamic library")) {
		if ($_PRINTDATA && !$ajaxProvider) echo "<div class='ui-state-error ui-corner-all'><span class='ui-icon ui-icon-script'></span>$msg ($file:$line)</div>";
		if (isset($errLog)) fwrite($errLog,"(".date("d/m/y H:i:s").") $msg [$file:$line]\r\n");
		return true;
	}
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

//Maintenance Page
if ($_SETUP == false && $config->getNode('site','enabled') != true && !strstr($_SERVER['REQUEST_URI'],"/admin/") && !strstr($_SERVER['REQUEST_URI'],"/acp2/") && !isset($maintPage)) {
	header("Location: ".$config->getNode("paths","root")."/errors/maintenance.php");
	die();
}

//Load Classes
require_once(dirname(__FILE__)."/includes/Item.class.php");
debug_message("Item Class Definition Loaded.");
require_once(dirname(__FILE__)."/includes/Cart.class.php");
debug_message("Cart Class Definition Loaded.");
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
require_once(dirname(__FILE__)."/includes/Feature.class.php");
debug_message("Feature Class Definition Loaded.");
//Load Function Replacements (PHP 4)
require_once(dirname(__FILE__)."/includes/json_encode.inc.php");
debug_message("json_encode Function Definition Loaded.");
require_once(dirname(__FILE__)."/includes/file_put_contents.inc.php");
debug_message("file_put_contents Function Definition Loaded.");
require_once(dirname(__FILE__)."/includes/file_get_contents.inc.php");
debug_message("file_get_contents Function Definition Loaded.");

$stats = new Stats();

//Check PHP Version
if (PHP_VERSION < "4.4.0") {
	if (!$_SETUP) init_err("Unsupported PHP Version. (v".PHP_VERSION.")");
} else {
	debug_message("PHP Version Supported (v".PHP_VERSION.")",true);
}

if (PHP_VERSION < "5.0.0") {
	register_shutdown_function("endGame");
	function endGame() {global $config,$basket; if (isset($config)) $config->__destruct(); if (isset($basket)) $basket->__destruct();}
}

//Check CURL Installed
if (extension_loaded("curl")) {
	debug_message("Curl Extension Installed",true);
} else {
	debug_message("CURL Extension not Available. PayPal disabled.");
	if (isset($config)) $config->setNode("paypal","enabled",false);
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
			$basket = new Cart(0);
			$basket->lock();
			debug_message("Basket Disabled for Crawlers");
		} else {
			$config->setNode('temp','crawler',false);
			$dbConn->query("INSERT INTO `basket` (obj) VALUES ('')");
			$basketid = $dbConn->insert_id();
			$basket = new Cart($basketid);
			debug_message("Created basket #$basketid",true);
		}
		if (!isset($_SERVER['REMOTE_ADDR'])) $ip = "127.0.0.1"; else $ip = $_SERVER['REMOTE_ADDR'];
		$dbConn->query("INSERT INTO `sessions` (session_id,basket,ip_addr) VALUES ('".session_id()."',$basketid,INET_ATON('".$ip."'))");
		debug_message("Remote IP: $ip");
	} else {
		if (array_search($_SERVER['HTTP_USER_AGENT'],explode($config->getNode('server','crawlerAgents'),"|"))) {
			$basket = new Cart(0);
			$basket->lock();
			$config->setNode('temp','crawler',true);
		} else {
			$dbConn->query("UPDATE `sessions` SET active='".$dbConn->time()."' WHERE session_id='".session_id()."'");
			$session = $dbConn->fetch($session);
			$basket = $dbConn->query("SELECT * FROM `basket` WHERE id='".$session['basket']."' LIMIT 1");
			$basket = $dbConn->fetch($basket);
			$basket = unserialize(base64_decode($basket['obj']));
			if (!is_object($basket)) {
				//Just load empty basket if shop disabled - Not used for major reasons
				if ($config->getNode('site','shopMode') == false) {
					$basket = new Cart(-1);
				} else {
					trigger_error("Fatal Error: Basket Corrupted. Aborting.");
					die();
				}
			}
			$basket->restore();
			$config->setNode('temp','crawler',false);
		}
	}
	
	debug_message("Loaded Session #".session_id(),true);
}

//Locale
if (isset($_SESSION['locale']) && !$_SETUP) $config->setNode("temp","country",$_SESSION['locale']);

//Theme Includer
function templateContent($id = -1) {
	global $config, $dbConn, $navigation_links, $page_title;
	if ($config->getNode("site","templateMode") == "core") {
		$page_content = ob_get_clean(); //Place in template
	
		$file = templateFinder(PAGE_TYPE,$id);
		require $file;
	} else {
		ob_end_flush();
	}
}

function templateFinder($page_type,$id = -1) {
	//Returns template file to include
	global $config;
	if (file_exists($config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/".$page_type.".".$id.".content.tpl.php")) {
		return $config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/".$page_type.".".$id.".content.tpl.php";
		
	} elseif (file_exists($config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/".$page_type.".content.tpl.php")) {
		return $config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/".$page_type.".content.tpl.php";
	} elseif (file_exists($config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/content.tpl.php")) {
		return $config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/content.tpl.php";
	} else {
		trigger_error("Could not locate template file",E_USER_ERROR);
		return;
	}
}

/*PLUGINS*/
//Each plugin that has /includes/preload.inc.php will have an option displayed here
$dir = opendir($config->getNode('paths','offlineDir')."/plugins");
while ($module = readdir($dir)) {
	if (file_exists($config->getNode('paths','offlineDir')."/plugins/".$module."/includes/preload.inc.php")) {
		include $config->getNode('paths','offlineDir')."/plugins/".$module."/includes/preload.inc.php";
	}
}

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
	$string = html_entity_decode(preg_replace_callback("/\[\[([a-z]*)?\]\]/i","placeholder_callback",$string));
	$string = str_replace("&apos;","'",$string);
	return $string;
}

function placeholder_callback($args) {
	global $config;
	return $config->getNode('messages',$args[1]);
}


//CRON RUNNER
if (!$_SETUP) {
	if ($config->getNode("server","lastCron") < time()-($config->getNode('server','cronFreq')*60) && !$ajaxProvider) {
		//Actually ran in footer if necessary
		$cron = true;
	} else {
		$cron = false;
	}
}

//Validate ACP Login
function acpusr_validate($requirement = NULL) {
	global $dbConn;
	if (!isset($_SESSION['acpusr'])) return false;
	$auth = base64_decode($_SESSION['acpusr']);
	$auth = explode("~",$auth);
	$GLOBALS['acp_uname'] = $auth[0];
	if ($requirement == NULL) {
		$result = $dbConn->query("SELECT pass FROM `acp_login` WHERE uname='".$auth[0]."' LIMIT 1");
	} else {
		$result = $dbConn->query("SELECT pass FROM `acp_login` WHERE uname='".$auth[0]."' AND $requirement=1 LIMIT 1");
	}
	if ($dbConn->rows($result) == 0) return false;
	$row = $dbConn->fetch($result);
	return sha1($row['pass']) == $auth[1];
}

//Tier 2 authentication
if (isset($requires_tier2) && $requires_tier2 == true) {
	if (!isset($_SESSION['adminAuth']) or $_SESSION['adminAuth'] == 0) {
		acpusr_tier2();
	} else {
		//Check login ID timer (15min disco)
		$result = $dbConn->query("SELECT last_tier2_login FROM `acp_login` WHERE id=".intval($_SESSION['adminAuth'])." LIMIT 1");
		if ($dbConn->rows($result) == 0) {
			acpusr_tier2();
		} else {
			$row = $dbConn->fetch($result);
			if (strtotime($row['last_tier2_login'])+900 < time()) {
				//Session expired
				acpusr_tier2();
			} else {
				//Yes, valid, extend timeout
				$dbConn->query("UPDATE `acp_login` SET last_tier2_login='".$dbConn->time()."' WHERE id=".intval($_SESSION['adminAuth'])." LIMIT 1");
			}
		}
	}
}

//Tier 2 login form
function acpusr_tier2() {
	global $config;
	?><html><body bgcolor='#E7E7E7'><h1>Second Tier Login Required</h1><p>The operation you are trying to perform requires you to enter the Instance Managemement password. This is an extra layer of security that was hard-coded into this system when it was installed, which is needed for you to perform major tasks that could potentially break the system. Even if you know the password, make sure that you know what you are doing before you access this area.</p><p>All access to this area is logged.</p><form action='<?php echo $config->getNode('paths','root');?>/admin/tier2_auth.php' method='post'>Password:&nbsp;<input type='password' name='passkey' id='passkey' /><input type="hidden" name="return" value="<?php echo $_SERVER['REQUEST_URI'];?>" /><input type="submit" /></form></body></html><?php
	exit;
}
?>