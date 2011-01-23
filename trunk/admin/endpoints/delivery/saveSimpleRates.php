<?php

//Process delivery rates
$USR_REQUIREMENT = "can_edit_delivery_rates";
require_once dirname(__FILE__)."/../header.php";

//Clear existing rates
for ($i=0; $config->isTree('deliveryTier'.$i); $i++) {
	$config->removeTree('deliveryTier'.$i);
}

//Convert to array if only one element
if (!is_array($_POST['name'])) {
	$_POST['name'] = array($_POST['name']);
	$_POST['price'] = array($_POST['price']);
}

//Add new rates
for ($i=0; isset($_POST['name'][$i]); $i++) {
	$config->addTree('deliveryTier'.$i, 'Delivery Tier '.$i);
	$config->setNode('deliveryTier'.$i, 'name', $_POST['name'][$i]);
	$config->setNode('deliveryTier'.$i, 'value', $_POST['price'][$i]);
}
?><script type="text/javascript">window.location='deliveryRates.php';</script>
