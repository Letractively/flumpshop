<?php
$USR_REQUIREMENT = "can_view_orders";
require_once dirname(__FILE__)."/../header.php";
$filter = $_GET['filter'];
$query = "SELECT * FROM `orders` WHERE false";

switch ($filter) {
	//All active orders
	case "active":
		$orderStats = array_keys($config->getNodes('orderstatus'));
		foreach ($orderStats as $status) { //Iterate through order statuses
			if (!is_int($status)) continue;
			$array = $config->getNode('orderstatus',$status);
			if ($array['active'] == true) { //true means that the status represents and open order
				$query .= " OR status='".$status."'";
			}
		}
		$query.= " ORDER BY status ASC";
		echo "<div class='ui-widget'><div class='ui-widget-header'>Active Orders</div><div class='ui-widget-content'>";
		break;
	//All active orders that are not assigned to a user
	case "unassigned":
		$orderStats = array_keys($config->getNodes('orderstatus'));
		$query .= " OR (false";
		foreach ($orderStats as $status) {
			if (!is_int($status)) continue;
			$array = $config->getNode('orderstatus',$status);
			if ($array['active'] == true) {
				$query .= " OR status='".$status."'";
			}
		}
		$query .= ")";
		$query.= " AND assignedTo=0";
		echo "<div class='ui-widget'><div class='ui-widget-header'>Unassigned Orders</div><div class='ui-widget-content'>";
		break;
	//All Closed Orders
	case "closed":
		$orderStats = array_keys($config->getNodes('orderstatus'));
		foreach ($orderStats as $status) {
			if (!is_int($status)) continue;
			$array = $config->getNode('orderstatus',$status);
			if ($array['active'] == false) {
				$query .= " OR status='".$status."'";
			}
		}
		$query.= " ORDER BY status ASC";
		echo "<div class='ui-widget'><div class='ui-widget-header'>Closed Orders</div><div class='ui-widget-content'>";
		break;
	//Open orders assigned to a given user
	case "assigned":
		$orderStats = array_keys($config->getNodes('orderstatus'));
		$query .= " OR (false";
		foreach ($orderStats as $status) {
			if (!is_int($status)) continue;
			$array = $config->getNode('orderstatus',$status);
			if ($array['active'] == true) {
				$query .= " OR status='".$status."'";
			}
		}
		$query .= ")";
		$query.= " AND assignedTo=".intval($_GET['id']);
		echo "<div class='ui-widget'><div class='ui-widget-header'>Assigned Orders</div><div class='ui-widget-content'>";
		break;
	//Orders that are assigned to the given user and closed
	case "closed_assigned":
		$orderStats = array_keys($config->getNodes('orderstatus'));
		$query .= " OR (false";
		foreach ($orderStats as $status) {
			if (!is_int($status)) continue;
			$array = $config->getNode('orderstatus',$status);
			if ($array['active'] == false) {
				$query .= " OR status='".$status."'";
			}
		}
		$query .= ")";
		$query.= " AND assignedTo=".intval($_GET['id']);
		echo "<div class='ui-widget'><div class='ui-widget-header'>Closed Orders</div><div class='ui-widget-content'>";
		break;
	//Orders made by a given customer
	case "customer":
		$orderStats = array_keys($config->getNodes('orderstatus'));
		$customer = intval($_GET['id']);
		$query .= " OR customer=".$customer;
		$query .= " ORDER BY status ASC";
		echo "<div class='ui-widget'><div class='ui-widget-header'>Orders For Customer #".$customer."</div><div class='ui-widget-content'>";
		break;
}

$orders = $dbConn->query($query);
if ($dbConn->rows($orders) == 0) {
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-info'></span>There are no orders that match the specified filters.</div>";
}
while ($order = $dbConn->fetch($orders)) {
	$status = $config->getNode('orderstatus',$order['status']);
	echo "<a href='order.php?id=".$order['id']."'>Order #".$order['id']." (".$status['name'].")</a><br />";
}

echo "</div></div>";
?>