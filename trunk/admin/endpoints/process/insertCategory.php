<?php
$ajaxProvider = true;
$_PRINTDATA = false;
require_once dirname(__FILE__)."/../../../preload.php";
$_PRINTDATA = false;
if (!isset($_SESSION['adminAuth']) || !$_SESSION['adminAuth']) {
	echo json_encode($_GLOBALS['errormsg']['adminAccessDenied']);
}
$_PRINTDATA = false;
$name = str_replace("'","''",$_POST['name']);
$description = str_replace("'","''",$_POST['description']);
$parent = str_replace("'","''",$_POST['parent']);

if ($name == "") {
	die(json_encode(-1));
}

$dbConn->query("INSERT INTO `category` (name,description,parent) VALUES ('$name','$description','$parent')");
echo "Category Added to database with ID ".$dbConn->insert_id();
?>