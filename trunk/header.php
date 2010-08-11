<?php
require_once dirname(__FILE__).'/preload.php';
echo '<!--Preload Executed in '.(microtime_float()-$time_start).' Seconds-->';
if (!isset($page_title)) $page_title = "Welcome";
if (!isset($_SUBPAGE)) $_SUBPAGE = true;

// Create content variables
//Meta Tags
$meta_tags = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
$meta_tags .= "<meta name='keywords' content='".$config->getNode('messages','keywords')."' />";
$meta_tags .= "<meta name='description' content='".$config->getNode('messages','tagline')."' />";
//Title
$title = $page_title;
$title .= " | ";
//preg_replace is slow - Use cache if possible
if (!$config->isNode('cache','name_stripped')) {
	$config->setNode('cache','name_stripped',preg_replace("/<(.*?)>/","",$config->getNode('messages','name')));
}
$title .= $config->getNode('cache','name_stripped');
//CSS Includes
$css_links = "<link rel='stylesheet' type='text/css' href='".$config->getNode("paths","root")."/style/jquery.css' />";
$css_links .= "<link rel='stylesheet' href='".$config->getNode('paths','root')."/style/cssprovider.php?theme=".$config->getNode("site", "theme")."&amp;sub=main' type='text/css' />";
// Subpages
if ($_SUBPAGE == true) {
	$css_links .= "<link rel='stylesheet' href='".$config->getNode('paths','root')."/style/cssprovider.php?theme=".$config->getNode("site", "theme")."&amp;sub=sub' type='text/css' />";
}
// Browser-dependant CSS Overrides
if (strstr($_SERVER['HTTP_USER_AGENT'],"Opera")) {
	$css_links .= "<link rel='stylesheet' type='text/css' href='".$config->getNode("paths","root")."/style/cssprovider.php?theme=".$config->getNode("site","theme")."&amp;sub=opera' />";
}
if (strstr($_SERVER['HTTP_USER_AGENT'],"MSIE 6.0")) {
	$css_links .= "<link rel='stylesheet' type='text/css' href='".$config->getNode("paths","root")."/style/cssprovider.php?theme=".$config->getNode("site","theme")."&amp;sub=ie6' />";
}
if (strstr($_SERVER['HTTP_USER_AGENT'],"MSIE 7.0")) {
	$css_links .= "<link rel='stylesheet' type='text/css' href='".$config->getNode("paths","root")."/style/cssprovider.php?theme=".$config->getNode("site","theme")."&amp;sub=ie7' />";
}
if (strstr($_SERVER['HTTP_USER_AGENT'],"MSIE 8.0")) {
	$css_links .= "<link rel='stylesheet' type='text/css' href='".$config->getNode("paths","root")."/style/cssprovider.php?theme=".$config->getNode("site","theme")."&amp;sub=ie8' />";
}
//JS Includes
$js_links = "<script src='".$config->getNode('paths','root')."/js/jquery.js' type='text/javascript'></script>";
$js_links .= "<script src='".$config->getNode('paths','root')."/js/jqueryui.js' type='text/javascript'></script>";
$js_links .= "<script src='".$config->getNode('paths','root')."/js/jquery.validate.min.js' type='text/javascript'></script>";
$js_links .= "<script src='".$config->getNode('paths','root')."/js/defaults.php' type='text/javascript'></script>";
//Plugin Includes
// Create a second OB
ob_start();
$dir = opendir($config->getNode('paths','offlineDir')."/plugins");
while ($module = readdir($dir)) {
	if (file_exists($config->getNode('paths','offlineDir')."/plugins/".$module."/includes/header.inc.php")) {
		include $config->getNode('paths','offlineDir')."/plugins/".$module."/includes/header.inc.php";
	}
}
$plugin_includes = ob_get_clean(); //Get OB Content and exit OB

//Tab Links
if (isset($_SUBPAGE) and $_SUBPAGE == false) $homeActive = "active"; else $homeActive = "";
if (stristr($_SERVER['REQUEST_URI'],"about.php")) $aboutActive = "active"; else $aboutActive = "";
if (stristr($_SERVER['REQUEST_URI'],"contact.php")) $contactActive = "active"; else $contactActive = "";
if (stristr($_SERVER['REQUEST_URI'],"basket.php")) $basketActive = "active"; else $basketActive = "";
$tab_links = "<li><a href='".$config->getNode('paths','root')."' class='".$homeActive."'>Home</a></li>";
$tab_links .= "<li><a href='".$config->getNode('paths','root')."/about.php' class='".$aboutActive."'>About</a></li>";
$tab_links .= "<li><a href='".$config->getNode('paths','root')."/contact.php' class='".$contactActive."'>Contact</a></li>";
if ($config->getNode('site','shopMode')) {
	$tab_links .= "<li><a href='".$config->getNode('paths','root')."/basket.php' class='".$basketActive."'>".$config->getNode('messages','basketHeader')."</a></li>";
}
// Login only shown if enabled
if ($config->getNode("site","loginTab")) {
	if (!isset($_SESSION['login']['active']) or $_SESSION['login']['active'] != true) {
		$tab_links .= '<li><a href="javascript:" onclick="loginForm();">Login</a></li>';
	} else {
		$tab_links .= '<li><a href="'.$config->getNode("paths","root").'/account">Account</a></li>';
	}
}

// Main Navigation
ob_start(); //Secondary OB
include(dirname(__FILE__)."/includes/index_nav.inc.php");
$navigation_links = ob_get_clean(); //Get OB content and exit OB

//Include Template
if (file_exists($config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/".PAGE_TYPE.".header.tpl.php")) {
	require $config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/".PAGE_TYPE.".header.tpl.php";
} else {
	require $config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/header.tpl.php";
}
//Unset some (not all, user may include them later)
unset($meta_tags,$title,$css_links,$js_links,$plugin_includes);
echo '<!--HEAD Generated in '.(microtime_float()-$time_start).' Seconds-->';
?>