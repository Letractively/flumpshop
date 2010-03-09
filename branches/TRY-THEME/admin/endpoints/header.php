<?php
require_once dirname(__FILE__)."/../../preload.php";
if ((!isset($_SESSION['adminAuth']) or $_SESSION['adminAuth'] !== true) and !isset($auth)) {
	header("HTTP/1.1 403 Forbidden");
	die($config->getNode("messages","adminDenied"));
}
if (isset($logger)) $prefix = "../"; else $prefix = "../../";

ob_flush();
flush();
?><html>
<head>
<link href="<?php echo $prefix;?>style-main.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $prefix;?>jqueryui.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $prefix;?>../style/jquery-overrides.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $prefix;?>../js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $prefix;?>../js/jqueryui-1-8.js"></script>
<script type="text/javascript" src="<?php echo $prefix;?>../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $prefix;?>../js/htmlbox.full.js"></script>
<script type="text/javascript" src="<?php echo $prefix;?>../js/defaults.php"></script>
<script type="text/javascript">$(document).ready(function() {$('input:submit, button').button();})</script>
<script>
function loader(str) {$('#dialog').html(str); loadDialog();}
function loadDialog() {$('#dialog').dialog();}
</script>
</head>
<body><div id="dialog"></div>