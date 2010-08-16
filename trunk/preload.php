<?php
//Some headers sent later & gzip compression
ob_start('ob_gzhandler');
header('Content-Type: text/html;charset=UTF-8');

ini_set('date.timezone','Europe/London');

if (isset($_SERVER['REQUEST_URI']) && stristr($_SERVER['REQUEST_URI'],'admin/setup')) $_SETUP = true; else $_SETUP = false;
if (!isset($ajaxProvider)) $ajaxProvider = false;

function init_err($msg) {
	global $config, $_SETUP, $INIT_DEBUG;
	if (!$_SETUP && !$INIT_DEBUG) {
		trigger_error($msg);
		if (is_object($config)) header('Location: '.$config->getNode('paths','root').'/errors/500.php?err='.base64_encode($msg).'&config=true');
		else header('Location: ./errors/500.php?err='.base64_encode($msg));
	}
	die($msg);
}
require_once(dirname(__FILE__).'/includes/Config.class.php');
require_once(dirname(__FILE__).'/includes/vars.inc.php');

if (isset($config)) {
	if($config->getNode('logs','errors')) $errLog = fopen($config->getNode('paths','logDir').'/errors.log','a+');
	if($config->getNode('server','debug')) $debugLog = fopen($config->getNode('paths','logDir').'/debug.log','a+');
}
function sys_error($level,$msg,$file,$line) {
	global $errLog, $_PRINTDATA, $ajaxProvider;
	if (!stristr($msg,'Unable to load dynamic library')) {
		if ($_PRINTDATA && !$ajaxProvider) echo '<div class="ui-state-error ui-corner-all"><span class="ui-icon ui-icon-script"></span>'.$msg.' ('.$file.':'.$line.')</div>';
		if (isset($errLog)) fwrite($errLog,'('.date('d/m/y H:i:s').') '.$msg.' ['.$file.':'.$line.']\r\n');
		return true;
	}
}

set_error_handler('sys_error');

function debug_message($msg,$check = false) {
	return;
}

if (!isset($_SESSION)) {
	session_start();
}

//Maintenance Page
if ($_SETUP == false && $config->getNode('site','enabled') != true && !strstr($_SERVER['REQUEST_URI'],'/admin/') && !strstr($_SERVER['REQUEST_URI'],'/acp2/') && !isset($maintPage)) {
	header('Location: '.$config->getNode('paths','root').'/errors/maintenance.php');
	die();
}

//Load Classes
require_once(dirname(__FILE__).'/includes/Item.class.php');
require_once(dirname(__FILE__).'/includes/Cart.class.php');
require_once(dirname(__FILE__).'/includes/Customer.class.php');
require_once(dirname(__FILE__).'/includes/User.class.php');
require_once(dirname(__FILE__).'/includes/Database.class.php');
require_once(dirname(__FILE__).'/includes/Category.class.php');
require_once(dirname(__FILE__).'/includes/Stats.class.php');
require_once(dirname(__FILE__).'/includes/Order.class.php');
//Load Function Replacements (PHP 4)
require_once(dirname(__FILE__).'/includes/json_encode.inc.php');
require_once(dirname(__FILE__).'/includes/file_put_contents.inc.php');
require_once(dirname(__FILE__).'/includes/file_get_contents.inc.php');
$stats = new Stats();

function loadClass($class_name) {
	require_once dirname(__FILE__).'/includes/'.$class_name.'.class.php';
}

//Check PHP Version
if (PHP_VERSION < '4.4.0') {
	if (!$_SETUP) init_err('Unsupported PHP Version. (v'.PHP_VERSION.')');
}

//Ensure they are shut down before dbConn is terminated
register_shutdown_function('endGame');
function endGame() {
	global $config,$basket;
	if (isset($config)) $config->__destruct();
	if (isset($basket)) $basket->__destruct();
	if (isset($user)) $user->customer->__destruct();
	if (isset($user)) $user->__destruct();
	if (isset($customer)) $customer->__destruct();
}

/*Actual User Initialisation*/
if ($_SETUP === false) {
	//Connect to Database
	$dbConn = db_factory();
	
	$session = $dbConn->query('SELECT basket FROM `sessions` WHERE session_id="'.session_id().'" LIMIT 1');
	if ($session === false && $_PRINTDATA) {
		trigger_error($dbConn->error());
	}
	if ($dbConn->rows($session) === 0) {
		//Build Session
		//Web Crawler Exception
		if (array_search($_SERVER['HTTP_USER_AGENT'],explode($config->getNode('server','crawlerAgents'),'|'))) {
			$config->setNode('temp','crawler',true);
			$basket = new Cart(0);
			$basket->lock();
		} else {
			$config->setNode('temp','crawler',false);
			$basket = new Cart(-1);
		}
		if (!isset($_SERVER['REMOTE_ADDR'])) $ip = '127.0.0.1'; else $ip = $_SERVER['REMOTE_ADDR'];
		$dbConn->query('INSERT INTO `sessions` (session_id,basket,ip_addr) VALUES ("'.session_id().'","'.$basket->getID().'",INET_ATON("'.$ip.'"))');
	} else {
		if (array_search($_SERVER['HTTP_USER_AGENT'],explode($config->getNode('server','crawlerAgents'),'|'))) {
			$basket = new Cart(0);
			$basket->lock();
			$config->setNode('temp','crawler',true);
		} else {
			$session = $dbConn->fetch($session);
			$basket = new Cart($session['basket']);
			$config->setNode('temp','crawler',false);
		}
	}
}

//Locale
if (isset($_SESSION['locale']) && !$_SETUP) $config->setNode('temp','country',$_SESSION['locale']);

//Theme Includer
function templateContent($id = -1) {
	global $config, $dbConn, $navigation_links, $page_title;
	if ($config->getNode('site','templateMode') === 'core') {
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
	$dir = $config->getNode('paths','offlineDir').'/themes/core/'.$config->getNode('site','theme').'/';
	if ($id !== -1 && file_exists($dir.$page_type.'.'.$id.'.content.tpl.php')) {
		return $dir.$page_type.'.'.$id.'.content.tpl.php';
		
	} elseif (file_exists($dir.$page_type.'.content.tpl.php')) {
		return $dir.$page_type.'.content.tpl.php';
	} else {
		return $dir.'content.tpl.php';
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
	$class = '';
	switch ($tree) {
		case 'paths':
			switch ($node) {
				case 'root':
					$class .= 'url ';
				case 'path':
					$class .= 'required ';
				break;
			}
			break;
		
	}
	return $class;
}

//Used for CMS pages
function placeholders($string) {
	global $config;
	$string = html_entity_decode(preg_replace_callback('/\[\[([a-z]*)?\]\]/i','placeholder_callback',$string));
	$string = str_replace('&apos;','\'',$string);
	return $string;
}

function placeholder_callback($args) {
	global $config;
	return $config->getNode('messages',$args[1]);
}


//CRON RUNNER
if (!$_SETUP) {
	if ($config->getNode('server','lastCron') < time()-($config->getNode('server','cronFreq')*60) && !$ajaxProvider) {
		//Actually ran in footer if necessary
		$cron = true;
	} else {
		$cron = false;
	}
}

require_once dirname(__FILE__).'/includes/acp.inc.php';
?>