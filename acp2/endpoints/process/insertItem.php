<?php
$ajaxProvider = true;
$_PRINTDATA = false;
require_once dirname(__FILE__)."/../../../preload.php";
if (!isset($_SESSION['adminAuth']) || !$_SESSION['adminAuth']) {
	echo json_encode($_GLOBALS['errormsg']['adminAccessDenied']);
}
$name = str_replace("'","''",$_POST['name']);
$description = nl2br(str_replace("'","''",$_POST['description']));
$price = str_replace("'","''",$_POST['price']);
$stock = str_replace("'","''",$_POST['stock']);
$category = str_replace("'","''",$_POST['category']);
$weight = str_replace("'","''",$_POST['weight']);

if ($name == "" or $description == "") {
	die(json_encode(-1));
}

$dbConn->query("INSERT INTO `products` (name,description,price,stock,category,weight) VALUES ('$name','$description','$price','$stock','$category','$weight')");
echo "Product Added to database with ID #".$dbConn->insert_id();
?>