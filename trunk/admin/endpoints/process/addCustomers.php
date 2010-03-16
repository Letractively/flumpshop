<?php
can_add_customers
require_once "../header.php";

if ($_GET['method'] != "manual") {
	die("Invalid request.");
} else {
	for ($i = 1; isset($_POST['customer'.$i.'_email']); $i++) {
		//Vars
		$name = $_POST['customer'.$i.'_name'];
		$address1 = $_POST['customer'.$i.'_address1'];
		$address2 = $_POST['customer'.$i.'_address2'];
		$address3 = $_POST['customer'.$i.'_address3'];
		$postcode = $_POST['customer'.$i.'_postcode'];
		$country = $_POST['customer'.$i.'_country'];
		$email = $_POST['customer'.$i.'_email'];
		if (!(empty($name) 
			&& empty($address1)
			&& empty($address2)
			&& empty($address3)
			&& empty($postcode)
			&& empty($country)
			&& empty($email))
			) {
			$customer = new Customer();
			$customer->populate($name,$address1,$address2,$address3,$postcode,$country,$email);
			echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Added Customer #".$customer->getID()."</div>";
		}
	}
}
require "../clients/addCustomers.php";
?>