<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";
if (!isset($_SESSION['adminAuth']) || $_SESSION['adminAuth'] == false) die($config->getNode('messages','adminDenied'));
$customer = new Customer(intval($_GET['id']));
?>
<div class="ui-widget">
	<div class="ui-widget-header">Details For Customer #<?php echo $customer->getID(); ?></div>
    <div class="ui-widget-content">
    	Name: <?php echo $customer->getName(); ?>
    	<div class="ui-widget-header">Address</div>
        <div class="ui-widget-content"><?php echo $customer->getAddress();?></div>
        <a href="javascript:void(0);" onclick="loader(loadMsg('Loading Content...'));window.location = './endpoints/orders/listOrders.php?filter=customer&id=<?php echo $customer->getID(); ?>';">Orders by this User</a>
    </div>
</div>