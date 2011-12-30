<?php
//To ensure correct order data, changing the Customer fields ALWAYS generates a new customer object
$ajaxProvider = true;
require_once dirname(__FILE__)."/../preload.php";

if (!isset($_SESSION['login']['active']) or $_SESSION['login']['active'] == false) {
	die($config->getNode('messages','loginNeeded'));
}
$user = new User($_SESSION['login']['id']);
debug_message("User Loaded");

$customer = new Customer();
debug_message("Customer Created");

$customer->populate($_POST['name'],$_POST['address1'],$_POST['address2'],$_POST['address3'],$_POST['postcode'],$_POST['country'],$_POST['email']);
debug_message("Customer Populated");

$user->replaceCustomerObj($customer);
debug_message("User Customer Details Updated.");

echo "<div class='ui-widget'><div class='ui-widget-header'>Default Contact Information</div>";
echo "<div class='ui-widget-content'>".$user->customer->getName()."<br />";
echo $user->customer->getAddress()."<hr />";
echo $user->customer->getEmail()."</div></div>";

if (!$user->customer->deliverySupported()) {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>".$config->getNode('messages','countryNotSupported')."</div>";
}
?>