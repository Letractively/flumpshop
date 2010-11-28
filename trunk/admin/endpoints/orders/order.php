<?php
$USR_REQUIREMENT = "can_view_orders";
require_once "../header.php";

$order = intval($_GET['id']);

$order = $dbConn->query("SELECT * FROM `orders` WHERE id='$order' LIMIT 1");

if (!$order or $dbConn->rows($order) == 0) {
	die("<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>I couldn't find the selected order!</div>");
}

$order = $dbConn->fetch($order);
$status = $config->getNode('orderstatus',$order['status']);
?>
<div id="saverTxt"></div>
<div class="ui-widget" id="orderData">
	<div class="ui-widget-header">Details for Order #<?php echo $order['id'];?></div>
	<?php
	if ($order['assignedTo'] == 0) {
		?>
		<div class="ui-state-highlight"><span class="ui-icon ui-icon-tag"></span>This order isn't assigned to anyone yet. <a href='assignOrder.php?oid=<?php echo $order['id'];?>&uid=<?php echo $acp_uid;?>'>Accept this order</a></div>
		<?php
	} elseif ($order['assignedTo'] == $acp_uid) {
		?>
		<div class="ui-state-highlight"><span class="ui-icon ui-icon-tag"></span>This order is assigned to you</div>
		<?php
	}
	?>
	<div class='ui-state-highlight'><span class='ui-icon ui-icon-flag'></span>Status: 
	<select name="orderStatus" style="display:inline;" onchange="updateStatus(this.value);">
	<option value="<?php echo $order['status'];?>"><?php echo $status['name']; ?></option>
	<option disabled="disabled">-----</option>
	<?php
	$orderStats = array_keys($config->getNodes('orderstatus'));
	foreach ($orderStats as $status) {
		if (!is_int($status)) continue;
		$array = $config->getNode('orderstatus',$status);
		echo "<option value='".$status."'>".$array['name']."</option>";
	}
	?>
	</select></div>
    <div class="ui-widget-content">Customer ID: <?php echo $order['shipping'];?> <a href='customer.php?id=<?php echo $order['shipping'];?>'>View Details</a><br />
    <strong>Order Summary</strong><br />
    <?php
	$orderBasket = $dbConn->query("SELECT * FROM `basket` WHERE id='".$order['basket']."' LIMIT 1");
	if (!$orderBasket or $dbConn->rows($orderBasket) == 0) {
		die("<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>I couldn't find the basket for the selected order!</div>");
	}
	$orderBasket = $dbConn->fetch($orderBasket);
	$orderBasket = new Cart($row['id']);
	echo $orderBasket->listItems("ORDER");
	?>
</div>
<script type="text/javascript">
function updateStatus(statusid) {
	$('#saverTxt').html('Updating Status...').addClass('ui-state-highlight');
	$.ajax({url:"updateStatus.php?order=<?php echo $order['id'];?>&status="+statusid,success:function(){$('#saverTxt').html("<span class='ui-icon ui-icon-circle-check'></span>Status Updated!")}});
}
</script>
</body></html>