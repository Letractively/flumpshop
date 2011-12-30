<?php
require_once "../header.php";

if ($acp_uid != $_GET['uid']) {
	//Attempting to assign to other user, needs assign permissions
	if (!acpusr_validate('can_assign_orders')) {
		$msg = $config->getNode("messages","adminDenied");
	}
} elseif (!acpusr_validate('can_edit_orders')) { //Attempting to assign to self, needs edit permissions
		$msg = $config->getNode("messages","adminDenied");
} else {
	$result = $dbConn->query("SELECT assignedTo FROM orders WHERE id=".intval($_GET['oid'])." LIMIT 1");
	$row = $dbConn->fetch($result);
	//Can only assign to self with assign permission if not already assigned
	if ($row['assignedTo'] != 0 and !acpusr_validate('can_assign_orders')) {
		$msg = $config->getNode("messages","adminDenied");
	} else {
		$dbConn->query("UPDATE orders SET assignedTo='".intval($_GET['uid'])."' WHERE id=".intval($_GET['oid'])." LIMIT 1");
		
		$msg = "<div class='ui-state-highlight'><span class='ui-icon ui-icon-tag'></span>Order ".$_GET['oid']." has been assigned.</div>";
	}
}

header("Location: ../switchboard/orders.php?msg=".$msg);
?>