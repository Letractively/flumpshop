<?php
$USR_REQUIREMENT = "can_view_orders";
require_once "../header.php";

$order = intval($_GET['id']);

$order = new Order($order);

if (!$order->getExists()) {
	die("<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>I couldn't find the selected order!</div>");
}
//Get the textual name for this order status
$status = $config->getNode('orderstatus', $order->status);
?>
<div id="saverTxt"></div>
<div class="ui-widget" id="orderData">
	<div class="ui-widget-header">Details for Order #<?php echo $order>id; ?></div>
	<?php
	if ($order->assigned_to == 0) {
		//Not assigned yet
		echo '<div class="ui-state-highlight"><span class="ui-icon ui-icon-tag"></span>This order isn\'t assigned to anyone yet.'.
			' <a href="assignOrder.php?oid='.$order->id.'&uid='.$acp_uid.'">Accept this order</a></div>';
	} elseif ($order->assigned_to == $acp_uid) {
		//Assigned to current user
		echo '<div class="ui-state-highlight"><span class="ui-icon ui-icon-tag"></span>This order is assigned to you</div>';
	} else {
		//Assigned to other user
		echo '<div class="ui-state-highlight"><span class="ui-icon ui-icon-tag"></span>This order is assigned to operator #'.
			$order->assigned_to.'</div>';
	}
	?>
	<div class='ui-state-highlight'><span class='ui-icon ui-icon-flag'></span>Status:
		<select name="orderStatus" style="display:inline;" onchange="updateStatus(this.value);">
			<option value="<?php echo $order->status; ?>"><?php echo $status['name']; ?></option>
			<option disabled="disabled">-----</option>
			<?php
			$orderStats = array_keys($config->getNodes('orderstatus'));
			foreach ($orderStats as $status) {
				if (!is_int($status))
					continue;
				$array = $config->getNode('orderstatus', $status);
				echo "<option value='" . $status . "'>" . $array['name'] . "</option>";
			}
			?>
		</select>
	</div>
    <div class="ui-widget-content">Customer ID: <?php echo $order->shipping; ?>
		<a href='customer.php?id=<?php echo $order->shipping; ?>'>View Details</a><br />
		<strong>Order Summary</strong><br />
		<?php
			//Get the basket object for the order
			$oBasket = $order->getBasketObj();
			if (!$oBasket->exists) {
				die("<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>I couldn't find the basket for the selected order!</div>");
			}
			echo $oBasket->listItems("ORDER");
		?>
	</div>
	<div class="ui-widget">
		<strong>More Information</strong><br />
		<a href="proforma.php?id=<?php echo $order->id?>">View Proforma Invoice</a><br />
		<a href="createScreen.php?id=<?php echo $order->id?>">Edit Order</a><br />
	</div>
</div>
<script type="text/javascript">
	function updateStatus(statusid) {
		$('#saverTxt').html('Updating Status...').addClass('ui-state-highlight');
		$.ajax({url:"updateStatus.php?order=<?php echo $order->id; ?>&status="+statusid,success:function(){$('#saverTxt').html("<span class='ui-icon ui-icon-circle-check'></span>Status Updated!")}});
}
</script>
</body></html>