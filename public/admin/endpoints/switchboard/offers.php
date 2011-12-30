<?php
require_once "../header.php";

?><h1>Manage Offers</h1>
<p>A great way to pursuade more visitors to buy from you, setting up special offers and discount codes are a powerful and simple tool to set up and configure. Simply choose an option below to get started.</p>
<div class="ui-state-highlight">
<ul>
	<li>Put a product on special offer</li>
	<li>Put all products in a category on special offer</li>
	<li>Schedule a future special offer on a product</li>
	<li>Schedule a future special offer on a category</li>
	<li>Create a new coupon code</li>
	<li>Advanced: 
		<ul>
			<li>View a list of current special offers</li>
			<li>View a list of past special offers</li>
			<li>View a list of current coupon codes</li>
		</ul>
	</li>
</ul>
</div>
<?php
if (isset($_GET['msg'])) echo $_GET['msg'];
?>
<div class='ui-widget-header'>I want to...</div>
<div class='ui-widget-content'>
	<ul><?php
	if (acpusr_validate('can_edit_products')) {
		?>
		<li><a href="../offers/createOffer.php" onclick="loader('Please wait...','Starting Wizard...');">Put a product on special offer</a></li>
		<li><a href="../orders/listOrders.php?filter=closed_assigned&id=<?php echo $acp_uid;?>" onclick="loader('Please wait...','Loading Data...');">View My Closed Orders</a></li>
		<li><a href="../orders/listOrders.php?filter=unassigned" onclick="loader('Please wait...','Loading Data...');">View All Unassigned Orders</a></li>
		<?php
	}
	if (acpusr_validate('can_create_orders')) {
		?>
		<li><a href="../orders/createScreen.php" onclick="loader('Please wait...','Loading Form...');">Create a new order</a></li>
		<?php
	}
	?>
	</ul>
</div>
<div class="ui-widget-header">Advanced Tools</div>
<div class="ui-widget-content">
	<ul>
		<?php
		if (acpusr_validate('can_view_orders')) {
			?>
			<li><a href="../orders/queryOrder.php" onclick="loader('Please wait...','Loading Contant...');">Find order by ID</a></li>
			<li></li>
			<?php
		}
		?>
	</ul>
</div>
</body></html>