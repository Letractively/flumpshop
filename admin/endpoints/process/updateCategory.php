<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";
if (!isset($_SESSION['adminAuth']) || !$_SESSION['adminAuth']) {
	echo json_encode($_GLOBALS['errormsg']['adminAccessDenied']);
}

$id = intval($_POST['catid']);
$name = str_replace("'","''",$_POST['name']);
$description = str_replace("'","''",$_POST['description']);
$parent = str_replace("'","''",$_POST['parent']);
if ($name == "") {
	die(json_encode(-1));
}

$dbConn->query("UPDATE `category` SET name='$name', description='$description', parent='$parent' WHERE id='$id' LIMIT 1");
?>
<div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Category Updated</div>