<?php
require_once dirname(__FILE__)."/../../../preload.php";

$lowerBound = $_POST['lowerBound'];
$upperBound = $_POST['upperBound'];
$price = $_POST['price'];
$countries = array();
for ($i = 0; isset($_POST['country'.$i]); $i++) {
	$dbConn->query("INSERT INTO `delivery` (country,lowerbound,upperbound,price) VALUES ('".$_POST['country'.$i]."',$lowerBound,$upperBound,$price)");
}
?>
<br /><?php include dirname(__FILE__)."/../delivery/deliveryRates.php";?>