<?php
$USR_REQUIREMENT = "can_edit_delivery_rates";

require_once dirname(__FILE__)."/../header.php";

$lowerBound = $_POST['lowerBound'];
$upperBound = $_POST['upperBound'];
$price = $_POST['price'];
$countries = array();

if ($_POST['lowerBound'] == "" or $_POST['upperBound'] == "" or $_POST['price'] == "") {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>'lowerBound', 'upperBound' and 'price' are all required.</div>";
} else {
	for ($i = 0; isset($_POST['country'.$i]); $i++) {
		if ($_POST['country'.$i] != "") {
			$dbConn->query("INSERT INTO `delivery` (country,lowerbound,upperbound,price) VALUES ('".$_POST['country'.$i]."',$lowerBound,$upperBound,$price)");
		}
	}
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Delivery Rate added to Database</div>";
}
include dirname(__FILE__)."/../delivery/deliveryRates.php";?>