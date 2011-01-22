<?php
require_once dirname(__FILE__)."/../../../preload.php";
require_once dirname(__FILE__)."/../Upgrade.class.php";
if ((!isset($_SESSION['adminAuth']) or $_SESSION['adminAuth'] !== true) and !isset($auth)) {
	header("HTTP/1.1 403 Forbidden");
	die($config->getNode("messages","adminDenied"));
}

if (file_exists($config->getNode('paths','offlineDir')."/upgrade_v".$_SESSION['latestVersion'].".fml")) {
	$package = unserialize(base64_decode(file_get_contents($config->getNode('paths','offlineDir')."/upgrade_v".$_SESSION['latestVersion'].".fml")));
	$upgrade = $package['upgrade'];
}
?><html>
<head>
<link href="../../style-main.css" rel="stylesheet" type="text/css" />
<link href="../../jqueryui.css" rel="stylesheet" type="text/css" />
<link href="../../../style/jquery-overrides.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/jqueryui-1-8.js"></script>
<script type="text/javascript" src="../../../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../../../js/defaults.php"></script>
<script type="text/javascript">$(document).ready(function() {$('input:submit, button').button();})</script>
<script>
function loader(str) {$('#dialog').html(str); loadDialog();}
function loadDialog() {$('#dialog').dialog();}
</script>
</head>
<body><div id="dialog"></div>