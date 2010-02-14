<?php
if (!isset($_SESSION)) session_start();
$_SETUP = true;
require_once dirname(__FILE__)."/../../includes/vars.inc.php";
//Process Login
if (isset($_POST['pass'])) {
	if (md5($_POST['pass']) == $config->getNode("site","password")) {
		$_SESSION['adminAuth'] = true;
		header("Location: ./");
		die();
	}
}
//Not Logged In
if ((!isset($_SESSION['adminAuth']) or $_SESSION['adminAuth'] !== true) and file_exists(dirname(__FILE__)."/../../conf.txt")) {
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
Please enter your username and password to continue...
<table>
<tr><td><label for='uname'>Username: </label></td><td><input type="text" name="uname" id="uname" disabled="disabled" /></td></tr>
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
    <div class="header">flump<span class='header2'>shop</span> setup</div>
    Powered by Flumpnet<br />
    <!--<a href="javascript:void(0);">Expand All</a> | <a href="javascript:void(0);"> Collapse All</a><br />--><br />
    <div id="navContainer">
    <div id="navAccordion">
        <div class="head">Welcome</div>
        <ul>
        	<li><a href="?frame=leftFrame&p=1.1" id="1.1">Introduction</a></li>
        	<li><a href="?frame=leftFrame&p=1.2" id="1.2">Compatibility Checker</a></li>
            <li><a href="?frame=leftFrame&p=1.3">Customisation Level</a></li>
        </ul>
        <div class="head">Settings</div>
        <ul>
        	<li><a href="?frame=leftFrame&p=2.1" id="2.1">Paths and Directories</a></li>
            <li><a href="?frame=leftFrame&p=2.2" id="2.2">Database</a></li>
            <li><a href="?frame=leftFrame&p=2.3" id="2.3">About You</a></li>
        </ul>
        <div class="head">Customise</div>
        <ul><?php
        	if (isset($_SESSION['stage']['security']) and $_SESSION['stage']['security']) {
				echo '<li><a href="?frame=leftFrame&p=3.1" id="3.1">Security Settings</a></li>';
			}
			if (isset($_SESSION['stage']['shop']) and $_SESSION['stage']['shop']) {
				echo '<li><a href="?frame=leftFrame&p=3.2" id="3.2">Shop Settings</a></li>';
			}
			//Order Status Removed
            if (isset($_SESSION['stage']['paypal']) and $_SESSION['stage']['paypal']) {
				echo '<li><a href="?frame=leftFrame&p=3.4" id="3.4">PayPal Settings</a></li>';
			}
            if (isset($_SESSION['stage']['messages']) and $_SESSION['stage']['messages']) {
				echo '<li><a href="?frame=leftFrame&p=3.5" id="3.5">Predefined Messages</a></li>';
			}
            if (isset($_SESSION['stage']['pagination']) and $_SESSION['stage']['pagination']) {
				echo '<li><a href="?frame=leftFrame&p=3.6" id="3.6">Pagination Settings</a></li>';
			}
            if (isset($_SESSION['stage']['account']) and $_SESSION['stage']['account']) {
				echo '<li><a href="?frame=leftFrame&p=3.7" id="3.7">User Account Settings</a></li>';
			}
            if (isset($_SESSION['stage']['smtp']) and $_SESSION['stage']['smtp']) {
				echo '<li><a href="?frame=leftFrame&p=3.8" id="3.8">SMTP Server Settings</a></li>';
			}
            if (isset($_SESSION['stage']['logs']) and $_SESSION['stage']['logs']) {
				echo '<li><a href="?frame=leftFrame&p=3.9" id="3.9">Log Settings</a></li>';
			}
            if (isset($_SESSION['stage']['server']) and $_SESSION['stage']['server']) {
				echo '<li><a href="?frame=leftFrame&p=3.10" id="3.10">Advanced Server Settings</a></li>';
			}
            if (isset($_SESSION['stage']['tabs']) and $_SESSION['stage']['tabs']) {
				echo '<li><a href="?frame=leftFrame&p=3.11" id="3.11">Tab Settings</a></li>';
			}
            if (isset($_SESSION['stage']['widget_carousel']) and $_SESSION['stage']['widget_carousel']) {
				echo '<li><a href="?frame=leftFrame&p=3.12" id="3.12">Carousel Widget Settings</a></li>';
			}
            if (isset($_SESSION['stage']['viewItem']) and $_SESSION['stage']['viewItem']) {
				echo '<li><a href="?frame=leftFrame&p=3.13" id="3.13">Item View Settings</a></li>';
			}
        ?></ul>
        <div class="head">Finish</div>
        <ul>
        	<li><a href="?frame=leftFrame&p=4.1" id="4.1">Save Settings</a></li>
            <li><a href="?frame=leftFrame&p=4.2" id="4.2">Goodbye, and hello!</a></li>
        </ul>
    </div>
    </div>
    </center>
    <script type="text/javascript">
    $(document).ready(function() {$('#navAccordion').accordion({collapsible: true, active: false, autoHeight: false, icons: {'header': 'ui-icon-circle-arrow-e', 'headerSelected': 'ui-icon-circle-arrow-s'}, navigation: true, header: '.head'});});
	function loader(str) {
		parent.main.document.body.innerHTML = loadMsg(str);
	}
    </script>
</body>
</html><?php
		} elseif ($_GET['frame'] == "header") {
			//Header Frame
			?><html>
				<head><link href="../style-header.css" rel="stylesheet" type="text/css" /></head>
				<body>
				<p>Copyright &copy; 2009-2010 Flumpnet. All rights reserved.</p>
				<div class="right">
					<h1>flump<span class="header2">shop</span> setup</h1>
				</div>
				</body>
			  </html><?php
		} elseif ($_GET['frame'] == "main") {
			//Main Frame
			?><html>
				<head><link href="../style-main.css" rel="stylesheet" type="text/css" /><script type="text/javascript" language="javascript" src="../../js/jquery.js"></script></head>
				<body>
				<h1>Flumpshop Setup</h1>
                <p>Welcome to the Flumpshop setup wizard. I'm the Flumpnet robot, and I'll be here throughout the setup process to guide you through it.</p>
                <p>Setup can take anything from a few seconds, to half an hour, depending on how experienced you are with the system, and the level of customisation you require. In a moment, you will be asked what optional information you want to fill in, in order to customise and streamline your setup experience. After all, the first step towards a wonderful and popular ecommerce such as myself is a wonderful, friendly introduction, and a smooth, clean, clear first configuration experience, for the best first impression.</p>
                <p>In order to complete the setup wizard, you're going to, as a minimum, need this information available: </p>
                <ul>
                	<li>A physical location not publically accessible on the server which I have full permissions over</li>
                    <li>What type of Database you wish to use, and the relevant connection details</li>
                    <li>The country the main demographic resides in</li>
                    <li>An email address</li>
                    <li>The address of your registered office, or one at which you can be written to</li>
                </ul>
                <p>Once you've scoured through your brain, and possibly called tech support, click the button below and I'll perform a few basic tests to make sure that Flumpshop is compatible with the server environment.</p>
                <button onclick="parent.leftFrame.window.location='./index.php?frame=leftFrame&p=1.2'; window.location = './welcome/checker.php';">Continue</button>
                <script type="text/javascript">
				$(parent.frames[0].document.getElementById('1.1')).addClass('ui-state-active');
				</script>
				</body>
			  </html><?php
		}
	} else {
		?><html><head><title>Flumpshop | Setup</title></head>
			<frameset cols="252px,*" framespacing="0" border="0" frameborder="0" frameborder="no" border="0">
			<frame name="leftFrame" id="leftFrame" src="?frame=leftFrame#welcome" scrolling="yes" frameborder="0" marginwidth="0" marginheight="0" border="no" />
			<frameset rows="60px,*" framespacing="0" border="0" frameborder="0" frameborder="no" border="0">
				<frame src="?frame=header" name="header" id="header" scrolling="no" noresize="noresize" frameborder="0" marginwidth="10" marginheight="0" border="no" />
				<frame src="?frame=main" name="main" id="main" scrolling="yes" frameborder="0" marginwidth="10" marginheight="10" border="no" />
			</frameset>
		  </frameset>
		  </html><?php
	}
}
?>