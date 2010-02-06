<?php
error_reporting(E_ALL);
function getNextStage($stage) {
	global $_SESSION;
	if (isset($_SESSION['stage']['security']) && $_SESSION['stage']['security'] && $stage < 3) {
		return "security.php";
	}
}

$INIT_DEBUG = false;
$_SETUP = true;
function debug_message($arg1 = "", $arg2 = false) {}
if (!isset($_SESSION)) session_start();
require_once dirname(__FILE__)."/../../../includes/Config.class.php";
require_once dirname(__FILE__)."/../../../includes/file_put_contents.inc.php";
require_once dirname(__FILE__)."/../../../includes/Database.class.php";
//Serializing prevents EPIC PHAIL
if (isset($_SESSION['config']) && is_string($_SESSION['config'])) {
	$_SESSION['config'] = unserialize($_SESSION['config']);
}
?><html>
<head><link href="../../style-main.css" rel="stylesheet" type="text/css" /><link href="../../jqueryui.css" rel="stylesheet" type="text/css" /><script type="text/javascript" language="javascript" src="../../../js/jquery.js"></script><script type="text/javascript" language="javascript" src="../../../js/jquery.form.js"></script><script type="text/javascript" language="javascript" src="../../../js/jqueryui-1-8.js"></script></head>
<body>