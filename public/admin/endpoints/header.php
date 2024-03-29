<?php
require_once dirname(__FILE__)."/../../../includes/preload.php";

if (isset($USR_REQUIREMENT)) {
	if (!acpusr_validate($USR_REQUIREMENT) and (!isset($auth) or $auth == true)) {
		header("HTTP/1.1 403 Forbidden");
		die($config->getNode("messages","adminDenied"));
	}
} else {
	if (!acpusr_validate() and (!isset($auth) or $auth == true)) {
		header("HTTP/1.1 403 Forbidden");
		die($config->getNode("messages","adminDenied"));
	}
}
if (isset($logger)) $prefix = "../"; else $prefix = "../../";

?><html>
<head>
<link href="<?php echo $prefix;?>style-main.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $prefix;?>jqueryui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $prefix;?>../js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $prefix;?>../js/jqueryui.js"></script>
<script type="text/javascript" src="<?php echo $prefix;?>../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $prefix;?>../js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo $prefix;?>../js/defaults.php"></script>
<script type="text/javascript">$(document).ready(function() {$('input:submit, button').button();<?php if (!strstr($_SERVER['REQUEST_URI'],"varMan.php") and !isset($noPreValidate)) echo "$('form').each(function(i){\$(this).validate();});"; //Breaks Configuration Manager?>});</script>
<script>
function loader(str,title) {$('#dialog').html("<center><img src='../../../images/loading.gif' /><br />"+str+"</center>").attr('title',title); loadDialog();}
function loadDialog() {$('#dialog').dialog();}
</script>
</head>
<body><div id="dialog"></div><?php if (!isset($backDisabled)) {?><p><button onClick="history.go(-1);" id="backButton">Back</button></p><?php }?>