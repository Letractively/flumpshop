<?php
$USR_REQUIREMENT = "can_view_customers";
require_once "../header.php";

$customer = new Customer(intval($_GET['id']));

?><div class="ui-widget">
	<div class="ui-widget-header">Details For Customer #<?php echo $customer->getID(); ?></div>
    <div class="ui-widget-content">
    	Name: <?php echo $customer->getName(); ?>
    	<div class="ui-widget-header">Address</div>
        <div class="ui-widget-content"><?php echo $customer->getAddress();?></div>
        <a href="listOrders.php?filter=customer&id=<?php echo $customer->getID(); ?>">Orders to this customer</a>
    </div>
</div>