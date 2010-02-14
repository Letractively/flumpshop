<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../preload.php";
$email = str_replace("'","''",$_GET['email']);
echo json_encode(!$dbConn->rows($dbConn->query("SELECT id FROM customers WHERE email='".$email."' LIMIT 1")));
?>