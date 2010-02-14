<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../preload.php";
require_once "./Upgrade.class.php";
$_SESSION['latestVersion'] = file_get_contents("http://flumpshop.googlecode.com/svn/updater/version.txt");

//Secure Redirect
if ($config->getNode('secure','admin') and $_SERVER['HTTPS'] == "off") {
	header("Location: ".$config->getNode('paths','secureRoot')."/admin/upgrade");
	exit;
}

//Process Login
if (isset($_POST['pass']) && md5($_POST['pass']) == $config->getNode("site","password")) {
	$_SESSION['adminAuth'] = true;
	header("Location: ./");
	die();
}
//Not Logged In
if (!isset($_SESSION['adminAuth']) or $_SESSION['adminAuth'] !== true) {
	//Login Page
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"
><head>
<style type="text/css">
body {background-color: #1e2b5b; color: #FFF; font: Arial, Helvetica, sans-serif;}
form {width: 300px; margin: 150px auto 0 auto;}
.header {font: "Trebuchet MS", Arial, Helvetica, sans-serif; font-size: 23px;}
.header2 {color: #c43131;}
.title {background-color: #0e78ee; padding: 0 5px; line-height: 1.5; font-size: 17px;}
.content {background-color: #e7e7e7; padding: 0 5px; color: #000; font-size: 12px;}
.content label {color: #1e2b5b;}
table td {text-align: right;}
input {border: 1px solid #1e2b5b; color: #1e2b5b; width: 200px;}
input.submit {width: auto; position: relative; left: 220px; border: 3px outset #1e2b5b; background: #FFF; color: #1e2b5b; font-size: 14px; font-weight: bold;}
</style>
<title>Flumpshop Login</title></head><body>
<form action="./" method="post">
<div class="header"><img src="../images/logo.jpg" alt="Flumpshop Logo" />flump<span class='header2'>shop</span></div>
<div class="title">please login...</div>
<div class="content">
Please enter the second tier password to continue...
<table>
<tr><td><label for='pass'>Password: </label></td><td><input type="password" name="pass" id="pass" /></td></tr>
</table>
<input type="submit" class="submit" value="Login" />
</div>
</form>
</body></html><?php
} else {
	//Logged In
	if (isset($_GET['frame'])) {
		if ($_GET['frame'] == "leftFrame") {
			//Left Frame
?><html>
<head>
<link href="../jqueryui.css" rel="stylesheet" type="text/css" />
<link href="../style-nav.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../../js/jqueryui-1-8.js"></script>
<script type="text/javascript" language="javascript" src="../../js/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" src="../../js/defaults.php"></script>
</head>
<body>
    <center><img src="../images/logo.jpg" />
    <div class="header">flump<span class='header2'>shop</span> <?php echo $config->getNode("site","version");?></div>
    Powered by Flumpnet<br />
    <div id="navContainer">
    <div id="navAccordion">
        <h3>Download</h3>
        	<p>Downloading...</p>
        <h3>Notices</h3>
        	<p>Important Information</p>
        <h3>Settings</h3>
        	<p>Things to Change</p>
        <h3>Upgrade</h3>
        	<p>Updating Flumpshop...</p>
        <h3>Finish</h3>
        	<p>Upgrade Complete!</p>
    </div>
    </div>
    </center>
    <script type="text/javascript">
    $(document).ready(function() {
							   $('#navAccordion').accordion({collapsible: true, active: false, autoHeight: false, icons: {'header': 'ui-icon-circle-arrow-e', 'headerSelected': 'ui-icon-circle-arrow-s'}});
							   });
    </script>
</body>
</html><?php
		} elseif ($_GET['frame'] == "header") {
			//Header Frame
			?><html>
				<head><link href="../style-header.css" rel="stylesheet" type="text/css" /></head>
				<body>
				<h1 class="title">UPGRADE WIZARD</h1>
				<p class="version">Current Version: v<?php echo $config->getNode("site","version");?></p>
				<div class="right">
					<h1>flump<span class="header2">powered</span></h1>
                    <p><a href='http://theflump.com' target='_blank'>TheFlump</a> | <a href='http://flumpshop.googlecode.com' target='_blank'>Flumpshop</a> | <a href='../' target="_top">Admin CP</a> | <a href='../../'>Home</a></p>
				</div>
				</body>
			  </html><?php
		} elseif ($_GET['frame'] == "main") {
			//Main Frame
			?><html>
				<head><link href="style-main.css" rel="stylesheet" type="text/css" /><link href="jqueryui.css" rel="stylesheet" type="text/css" /><script src="../js/jquery.js"></script><script src="../js/jqueryui.js"></script></head>
                <script>function loadDialog() {$('#dialog').dialog();}</script>
				<body>
				
				</body>
			  </html><?php
		}
	} else {
		?><html><head><title>Flumpshop | Upgrade Wizard</title></head>
			<frameset cols="252px,*" framespacing="0" border="0" frameborder="0" frameborder="no" border="0">
			<frame name="leftFrame" id="leftFrame" src="?frame=leftFrame" scrolling="yes" frameborder="0" marginwidth="0" marginheight="0" border="no" />
			<frameset rows="60px,*" framespacing="0" border="0" frameborder="0" frameborder="no" border="0">
				<frame src="?frame=header" name="header" id="header" scrolling="no" noresize="noresize" frameborder="0" marginwidth="10" marginheight="0" border="no" />
				<frame src="pages/download.php" name="main" id="main" scrolling="yes" frameborder="0" marginwidth="10" marginheight="10" border="no" />
			</frameset>
		  </frameset>
		  </html><?php
	}
}
?>