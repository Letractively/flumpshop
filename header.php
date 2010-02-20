<?php
//Some headers sent later
ob_start();
require_once dirname(__FILE__)."/preload.php";
if (!isset($page_title)) $page_title = "Welcome";
if (!isset($_SUBPAGE)) $_SUBPAGE = true;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='keywords' content='<?php echo $config->getNode('messages','keywords');?>' />
<meta name='description' content='<?php echo $config->getNode('messages','tagline');?>' />
<title><?php echo $config->getNode('messages','name');?> | <?php echo $page_title;?></title>
<link rel='stylesheet' href='<?php echo $config->getNode('paths','root');?>/style/cssprovider.php?theme=default&sub=main' type='text/css' /><?php
if (!isset($_SUBPAGE) or $_SUBPAGE == true) {
	?><link rel='stylesheet' href='<?php echo $config->getNode('paths','root');?>/style/cssprovider.php?theme=default&sub=sub' type='text/css' /><?php
}?><link rel='stylesheet' href='<?php echo $config->getNode('paths','root');?>/style/style_carousel.css' type='text/css' />
<script src='<?php echo $config->getNode('paths','root');?>/js/jquery.js' type='text/javascript'></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jcarousel.js"></script> 
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jqueryui.js"></script> 
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jeditable.js"></script> 
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/additional-methods.js"></script> 
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jquery.validate.password.js"></script> 
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jquery.init.js"></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jMyCarousel.pack.js"></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/defaults.php"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $config->getNode('paths','root');?>/style/jquery.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $config->getNode('paths','root');?>/style/jquery-overrides.css" /><?php
//Browser-dependant CSS Overrides
if (preg_match("/^Opera/",$_SERVER['HTTP_USER_AGENT'])) {
	echo "<link rel='stylesheet' type='text/css' href='".$config->getNode("paths","root")."/style/cssprovider.php?theme=default&sub=opera' />";
}
if (preg_match("/MSIE 8\.0/",$_SERVER['HTTP_USER_AGENT'])) {
	echo "<link rel='stylesheet' type='text/css' href='".$config->getNode("paths","root")."/style/cssprovider.php?theme=default&sub=ie8' />";
}

/*PLUGINS*/
//Each plugin that has /includes/header.inc.php will have an option displayed here
$dir = opendir($config->getNode('paths','offlineDir')."/plugins");
while ($module = readdir($dir)) {
	if (file_exists($config->getNode('paths','offlineDir')."/plugins/".$module."/includes/header.inc.php")) {
		include $config->getNode('paths','offlineDir')."/plugins/".$module."/includes/header.inc.php";
	}
}
?></head>
<body>
<div id="container">
    <div id="header">
        <h1 id="site_name"><?php echo $config->getNode('messages','name');?></h1>
        <h2 id="site_tagline"><?php echo $config->getNode('messages','tagline');?></h2>
    </div><!--End Header-->
    <ul id="tabs">
        <li><a href="<?php echo $config->getNode('paths','root');?>">Home</a></li>
        <li><a href="<?php echo $config->getNode('paths','root');?>/about.php">About</a></li>
        <li><a href="<?php echo $config->getNode('paths','root');?>/contact.php">Contact</a></li>
        <li><a href="#">Login</a></li>
        <li><a href="<?php echo $config->getNode('paths','root');?>/basket.php">Cart</a></li>
        <li><a href="<?php echo $config->getNode('paths','root');?>/admin">Admin</a></li>
    </ul><!-- End Tabs-->
    <div id="category_container"><?php
		require "includes/index_nav.inc.php";
        ?></div><!-- End Navigation-->