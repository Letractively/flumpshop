<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";
$filter = $_GET['filter'];
$query = "SELECT * FROM `orders` WHERE false";

switch ($filter) {
	case "active":
		$orderStats = array_keys($config->getNodes('orderstatus'));
		foreach ($orderStats as $status) {
			if (!is_int($status)) continue;
			$array = $config->getNode('orderstatus',$status);
			if ($array['active'] == true) {
				$query .= " OR status='".$status."'";
			}
		}
		$query.= " ORDER BY status ASC";
		echo "<div class='ui-widget'><div class='ui-widget-header'>Active Orders</div><div class='ui-widget-content'>";
		break;
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
	echo "There are no orders that match the specified filters.";
}
while ($order = $dbConn->fetch($orders)) {
	$status = $config->getNode('orderstatus',$order['status']);
	echo "<a href='javascript:void(0);' onclick=\"$('#adminContent').html(loadingString);$('#adminContent').load('./endpoints/orders/order.php?id=".$order['id']."',{},function() {\$('#".$order['id']."').editable('./endpoints/process/updateStatus.php',{loadurl: './endpoints/orders/statuses.php',type: 'select',submit: 'Save'})});\">Order #".$order['id']." (".$status['name'].")</a><br />";
}

echo "</div></div>";
?>