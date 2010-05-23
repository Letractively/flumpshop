<?php
require_once "../header.php";
?>
<h1>Manage Orders</h1>
<p>Here you can actually take, process and update orders.</p>
<?php
if (isset($_GET['msg'])) echo $_GET['msg'];
?>
<div class='ui-widget-header'>I want to...</div>
<div class='ui-widget-content'>
	<ul><?php
	if (acpusr_validate('can_view_orders')) {
		?>
		<li><a href="../orders/listOrders.php?filter=assigned&id=<?php echo $acp_uid;?>" onclick="loader('Please wait...','Loading Data...');">View My Active Orders</a></li>
		<li><a href="../orders/listOrders.php?filter=closed_assigned&id=<?php echo $acp_uid;?>" onclick="loader('Please wait...','Loading Data...');">View My Closed Orders</a></li>
		<li><a href="../orders/listOrders.php?filter=unassigned" onclick="loader('Please wait...','Loading Data...');">View All Unassigned Orders</a></li>
		<?php
	}
	?>
	</ul>
</div>
<div class="ui-widget-header">Advanced Tools</div>
<div class="ui-widget-content">
</div>
</body></html>