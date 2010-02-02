<?php
$INIT_DEBUG = false;
$_SETUP = true;
function debug_message($arg1 = "", $arg2 = false) {}
if (!isset($_SESSION)) session_start();
require_once dirname(__FILE__)."/../../../includes/Config.class.php";
//Serializing prefents EPIC PHAIL
if (isset($_SESSION['config'])) {
	$_SESSION['config'] = unserialize($_SESSION['config']);
}
?><html>
<head><link href="../../style-main.css" rel="stylesheet" type="text/css" /><link href="../../jqueryui.css" rel="stylesheet" type="text/css" /><script type="text/javascript" language="javascript" src="../../../js/jquery.js"></script><script type="text/javascript" language="javascript" src="../../../js/jqueryui-1-8.js"></script></head>
<body>