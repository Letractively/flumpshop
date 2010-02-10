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
<link rel='stylesheet' href='<?php echo $config->getNode('paths','root');?>/style/style.css' type='text/css' /><?php
if (!isset($_SUBPAGE) or $_SUBPAGE == true) {
	?><link rel='stylesheet' href='<?php echo $config->getNode('paths','root');?>/style/style-subpage.css' type='text/css' /><?php
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
<link rel="stylesheet" type="text/css" href="<?php echo $config->getNode('paths','root');?>/style/skins/rjc/skin.php" />
<link rel="stylesheet" type="text/css" href="<?php echo $config->getNode('paths','root');?>/style/jquery.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $config->getNode('paths','root');?>/style/jquery-overrides.css" />
</head>
<body>
<div id='header'>
<div id="title">
<div onclick='window.location = "<?php echo $config->getNode('paths','root');?>";' style="cursor: pointer"><h1><?php echo strtolower($config->getNode('messages','name')); ?></h1>
<h1 class='drop_shadow'><?php echo strtolower($config->getNode('messages','name')); ?></h1>
<h1 class='slogan'><?php echo $config->getNode('messages','tagline');?></h1></div>
</div>
<div id='search_container'><form action='<?php echo $config->getNode('paths','root');?>/search.php' onsubmit='return $(this).validate();' id='search_form'><input class='search_box' value='Search...' onfocus="if(this.value=='Search...'){this.value=''}" onblur="if(this.value==''){this.value='Search...'}" type='text' name='q' id='q' maxlength="150" /><div id='search_icon' onclick='$("#search_form").submit();'></div></form></div>
<div id='tab'>
<?php
//Home
if ($config->getNode("site", "homeTab") == true) {
	if (isset($_SUBPAGE) and $_SUBPAGE == false) {?>
    <a class='tab active' href='<?php echo $config->getNode('paths','root');?>'>home</a>
	<?php } else {?>
    <a class='tab' href='<?php echo $config->getNode('paths','root');?>'>home</a><?php }
}

//Basket
if ($config->getNode("site","shopMode")) {
	if (stristr($_SERVER['REQUEST_URI'],"basket.php")) {?><a class='tab active' href='<?php echo $config->getNode('paths','root');?>/basket.php'>cart</a><?php } else {?><a class='tab' href='<?php echo $config->getNode('paths','root');?>/basket.php'>cart</a><?php }
}

if ($config->getNode("site","loginTab")) {
	if (!isset($_SESSION['login']['active']) or $_SESSION['login']['active'] == false) echo "<a class='tab' onclick='loginForm();'>login</a>"; else {
		//Login/Account
		if (stristr($_SERVER['REQUEST_URI'],"/account")) {echo "<a class='tab active' href='".$config->getNode("paths","root")."/account/'>account</a>";} else {echo "<a class='tab' href='".$config->getNode("paths","root")."/account/'>account</a>";}
	}
}

//Contact
if (stristr($_SERVER['REQUEST_URI'],"contact.php")) {?><a class='tab active' href='<?php echo $config->getNode('paths','root');?>/contact.php'>contact</a><?php } else {?><a class='tab' href='<?php echo $config->getNode('paths','root');?>/contact.php'>contact</a><?php }

//About
if (stristr($_SERVER['REQUEST_URI'],"about.php")) {?><a class='tab active' href='<?php echo $config->getNode('paths','root');?>/about.php'>about</a><?php } else {?><a class='tab' href='<?php echo $config->getNode('paths','root');?>/about.php'>about</a><?php }

/*ADMIN*/ if (isset($_SESSION['adminAuth']) and $_SESSION['adminAuth'] == true) echo "<a class='tab' href='".$config->getNode('paths','root')."/admin'>admin</a>";
?></div>
</div>
<!--End Header-->
<div id='content_container_background'>
<div id="content_container">
<center><table><tr><?php
if (!isset($_SUBPAGE) or $_SUBPAGE == false) include $config->getNode("paths","path")."/includes/index_nav.inc.php";
echo '<td id="mainContent">';
if ($_PRINTDATA) echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-alert'></span>Debug Mode Enabled.</div>";?>