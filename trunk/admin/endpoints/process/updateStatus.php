<?php
$USR_REQUIREMENT = "can_edit_orders";

$ajaxProvider = true;
//Updates the status of an order
require_once dirname(__FILE__)."/../../../preload.php";
if (!isset($_SESSION['adminAuth']) || $_SESSION['adminAuth'] == false) die($config->getNode('messages','adminDenied'));

$val = $_POST['id'];

$status = $_POST['value'];

$dbConn->query("UPDATE `orders` SET status='$status' WHERE id='$val' LIMIT 1");
$name = $config->getNode('orderstatus',$status);
$name = $name['name'];
echo $name;
?>