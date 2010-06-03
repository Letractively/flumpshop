<?php
require_once dirname(__FILE__)."/preload.php";

if (!isset($page_title)) $page_title = "Welcome";
if (!isset($_SUBPAGE)) $_SUBPAGE = true;

if ($config->getNode("site","templateMode") == "core" && (
	file_exists($config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/".PAGE_TYPE.".header.tpl.php") or //Current Page Template
	file_exists($config->getNode("paths","offlineDir")."/themes/core/".$config->getNode("site","theme")."/header.tpl.php"))) { //Master Template
	//GroundUP Templater (Experimental)
	// Create content variables
	//Meta Tags
	$meta_tags = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	$meta_tags .= "<meta name='keywords' content='".$config->getNode('messages','keywords')."' />";
	$meta_tags .= "<meta name='description' content='".$config->getNode('messages','tagline')."' />";
	//Title
	$title = preg_replace("/<(.*?)>/","",$config->getNode('messages','name')); //preg strips HTML tags
	$title .= " | ";
	$title .= $page_title;
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
	$js_links .= "<script src='".$config->getNode('paths','root')."/js/additional-methods.js' type='text/javascript'></script>";
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
	
} else {
	//Old Header
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='keywords' content='<?php echo $config->getNode('messages','keywords');?>' />
<meta name='description' content='<?php echo $config->getNode('messages','tagline');?>' />
<title><?php echo preg_replace("/<(.*?)>/","",$config->getNode('messages','name')); //preg strips HTML tags?> | <?php echo $page_title;?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $config->getNode('paths','root');?>/style/jquery.css" /><?php
//THEME
?><link rel='stylesheet' href='<?php echo $config->getNode('paths','root');?>/style/cssprovider.php?theme=<?php echo $config->getNode("site", "theme");?>&amp;sub=main' type='text/css' /><?php
if ($_SUBPAGE == true) {
	?><link rel='stylesheet' href='<?php echo $config->getNode('paths','root');?>/style/cssprovider.php?theme=<?php echo $config->getNode("site", "theme");?>&amp;sub=sub' type='text/css' /><?php
}
?><script src='<?php echo $config->getNode('paths','root');?>/js/jquery.js' type='text/javascript'></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jqueryui.js"></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/additional-methods.js"></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/defaults.php"></script><?php

/*PLUGINS*/
//Each plugin that has /includes/header.inc.php will have an option displayed here
$dir = opendir($config->getNode('paths','offlineDir')."/plugins");
while ($module = readdir($dir)) {
	if (file_exists($config->getNode('paths','offlineDir')."/plugins/".$module."/includes/header.inc.php")) {
		include $config->getNode('paths','offlineDir')."/plugins/".$module."/includes/header.inc.php";
	}
}

//Browser-dependant CSS Overrides
if (strstr($_SERVER['HTTP_USER_AGENT'],"Opera")) {
	echo "<link rel='stylesheet' type='text/css' href='".$config->getNode("paths","root")."/style/cssprovider.php?theme=".$config->getNode("site","theme")."&amp;sub=opera' />";
}
if (strstr($_SERVER['HTTP_USER_AGENT'],"MSIE 6.0")) {
	echo "<link rel='stylesheet' type='text/css' href='".$config->getNode("paths","root")."/style/cssprovider.php?theme=".$config->getNode("site","theme")."&amp;sub=ie6' />";
}
if (strstr($_SERVER['HTTP_USER_AGENT'],"MSIE 7.0")) {
	echo "<link rel='stylesheet' type='text/css' href='".$config->getNode("paths","root")."/style/cssprovider.php?theme=".$config->getNode("site","theme")."&amp;sub=ie7' />";
}
if (strstr($_SERVER['HTTP_USER_AGENT'],"MSIE 8.0")) {
	echo "<link rel='stylesheet' type='text/css' href='".$config->getNode("paths","root")."/style/cssprovider.php?theme=".$config->getNode("site","theme")."&amp;sub=ie8' />";
}
?></head>
<body>
<div id="container">
    <div id="header">
    	<div onclick="window.location = '<?php echo $config->getNode('paths','root');?>';">
        	<h1 id="site_name"><?php echo $config->getNode('messages','name');?></h1>
        	<h2 id="site_tagline"><?php echo $config->getNode('messages','tagline');?></h2>
        </div>
    </div><!--End Header-->
	<div id="search_container">
        <form action='<?php echo $config->getNode('paths','root');?>/search.php' method='get' id='search_form'>
            <input type='text' name='q' id='q' value='Search...' onfocus='if(this.value=="Search..."){this.value="";}' onblur='if(this.value==""){this.value="Search...";}' />
            <input type='submit' id='search_submit' title='Click to search' value='&nbsp;&nbsp;' />
        </form>
    </div><!--End Search-->
    <ul id="tabs"><?php
if (isset($_SUBPAGE) and $_SUBPAGE == false) $homeActive = "active"; else $homeActive = "";
if (stristr($_SERVER['REQUEST_URI'],"about.php")) $aboutActive = "active"; else $aboutActive = "";
if (stristr($_SERVER['REQUEST_URI'],"contact.php")) $contactActive = "active"; else $contactActive = "";
if (stristr($_SERVER['REQUEST_URI'],"basket.php")) $basketActive = "active"; else $basketActive = "";
        ?><li><a href="<?php echo $config->getNode('paths','root');?>" class="<?php echo $homeActive;?>">Home</a></li>
        <li><a href="<?php echo $config->getNode('paths','root');?>/about.php" class="<?php echo $aboutActive;?>">About</a></li>
        <li><a href="<?php echo $config->getNode('paths','root');?>/contact.php" class="<?php echo $contactActive;?>">Contact</a></li><?php
        //Login only shown if enabled
		if ($config->getNode("site","loginTab")) {
			if (!isset($_SESSION['login']['active']) or $_SESSION['login']['active'] != true) {?>
				<li><a href="javascript:" onclick="loginForm();">Login</a></li><?php }
			else {?>
				<li><a href="<?php echo $config->getNode("paths","root");?>/account">Account</a></li><?php }
		}
		//Cart only shown if shop enabled
        if ($config->getNode("site","shopMode")) {?>
		<li><a href="<?php echo $config->getNode('paths','root');?>/basket.php" class="<?php echo $basketActive;?>">Cart</a></li><?php }
        //Admin only shown if Admin logged in
		if (isset($_SESSION['adminAuth']) and $_SESSION['adminAuth']) {?>
		<li><a href="<?php echo $config->getNode('paths','root');?>/admin">Admin</a></li><?php }
    ?></ul><!-- End Tabs-->
    <div id="category_container"><?php
		require "includes/index_nav.inc.php";
        ?></div><!-- End Navigation--><div id="content_container"><?php
}
?>