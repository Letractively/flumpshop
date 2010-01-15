<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";

if (isset($_GET['orderid'])) {
	$order = intval($_GET['orderid']);

	$order = $dbConn->query("SELECT * FROM `orders` WHERE id='$order' LIMIT 1");
	
	if (!$order or $dbConn->rows($order) == 0) {
		die("<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>I couldn't find the selected order!</div>");
	}
	
	$order = $dbConn->fetch($order);
	$status = $config->getNode('orderstatus',$order['status']);
	?>
	<div class="ui-widget">
		<div class="ui-widget-header">Details for Order #<?php echo $order['id'];?></div>
		<div class='ui-state-highlight'><span class='ui-icon ui-icon-flag'></span>Status: <span id="<?php echo $order['id']; ?>" style="display:inline;"><?php echo $status['name']; ?></span></div>
		<div class="ui-widget-content">Customer ID: <?php echo $order['customer'];?> <a href='javascript:void(0);' onclick="$('#adminContent').html(loadingString);$('#adminContent').load('./endpoints/orders/customer.php?id=<?php echo $order['customer']; ?>');">View Details</a><br />
		<strong>Order Summary</strong><br />
		<?php
		$orderBasket = $dbConn->query("SELECT * FROM `basket` WHERE id='".$order['basket']."' LIMIT 1");
		if (!$orderBasket or $dbConn->rows($orderBasket) == 0) {
			die("<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>I couldn't find the basket for the selected order!</div>");
		}
		$orderBasket = $dbConn->fetch($orderBasket);
		$orderBasket = unserialize($orderBasket['obj']);
		echo $orderBasket->listItems("ORDER");
		?>
	</div>
<?php
} else {
	?>
    <form action="queryOrder.php" method="get" onsubmit="if ($(this).valid()) $(this).ajaxSubmit({target: '#adminContent'}); return false;">
    	<label for="orderid">Order ID #</label><input type="text" name="orderid" id="orderid" class="ui-state-default required number" />
        <input type="submit" class="ui-state-default" />
    </form>
    <?php
}
?>