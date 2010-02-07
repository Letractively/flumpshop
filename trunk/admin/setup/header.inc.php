<?php
error_reporting(0);
//Works out the next stage to go to
function getNextStage($stage) {
	global $_SESSION;
	//Security
	if (isset($_SESSION['stage']['security']) && $_SESSION['stage']['security'] && $stage < 4) {
		return "security.php";
	}
	//Shop
	if (isset($_SESSION['stage']['shop']) && $_SESSION['stage']['shop'] && $stage < 5) {
		return "shop.php";
	}
	//Order Status
	if (isset($_SESSION['stage']['orderstatus']) && $_SESSION['stage']['orderstatus'] && $stage < 6) {
		return "orderstatus.php";
	}
	//PayPal
	if (isset($_SESSION['stage']['paypal']) && $_SESSION['stage']['paypal'] && $stage < 7) {
		return "paypal.php";
	}
	//Messages
	if (isset($_SESSION['stage']['messages']) && $_SESSION['stage']['messages'] && $stage < 8) {
		return "messages.php";
	}
	//Pagination
	if (isset($_SESSION['stage']['pagination']) && $_SESSION['stage']['pagination'] && $stage < 9) {
		return "pagination.php";
	}
	//Account
	if (isset($_SESSION['stage']['account']) && $_SESSION['stage']['account'] && $stage < 10) {
		return "account.php";
	}
	//SMTP
	if (isset($_SESSION['stage']['smtp']) && $_SESSION['stage']['smtp'] && $stage < 11) {
		return "smtp.php";
	}
	//Logs
	if (isset($_SESSION['stage']['logs']) && $_SESSION['stage']['logs'] && $stage < 12) {
		return "logs.php";
	}
	//Server
	if (isset($_SESSION['stage']['server']) && $_SESSION['stage']['server'] && $stage < 13) {
		return "server.php";
	}
	//Tabs
	if (isset($_SESSION['stage']['tabs']) && $_SESSION['stage']['tabs'] && $stage < 14) {
		return "tabs.php";
	}
	//Carousel
	if (isset($_SESSION['stage']['widget_carousel']) && $_SESSION['stage']['widget_carousel'] && $stage < 15) {
		return "widget_carousel.php";
	}
	//View Item
	if (isset($_SESSION['stage']['viewItem']) && $_SESSION['stage']['viewItem'] && $stage < 16) {
		return "viewItem.php";
	}
	return "finish.php";
}

$INIT_DEBUG = false;
$_SETUP = true;
function debug_message($arg1 = "", $arg2 = false) {}
if (!isset($_SESSION)) session_start();
require_once dirname(__FILE__)."/../../includes/Config.class.php";
require_once dirname(__FILE__)."/../../includes/file_put_contents.inc.php";
require_once dirname(__FILE__)."/../../includes/Database.class.php";
//Serializing prevents EPIC PHAIL
if (isset($_SESSION['config']) && is_string($_SESSION['config'])) {
	$_SESSION['config'] = unserialize($_SESSION['config']);
}
?><html>
<head><link href="../../style-main.css" rel="stylesheet" type="text/css" /><link href="../../jqueryui.css" rel="stylesheet" type="text/css" /><script type="text/javascript" language="javascript" src="../../../js/jquery.js"></script><script type="text/javascript" language="javascript" src="../../../js/jquery.form.js"></script><script type="text/javascript" language="javascript" src="../../../js/jqueryui-1-8.js"></script></head>
<body>