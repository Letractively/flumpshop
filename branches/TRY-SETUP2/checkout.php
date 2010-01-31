<?php
//Transfer Session to HTTPS
$vars = array_keys($_GET);
if (isset($vars[0]) && is_string($vars[0])) {
	if (isset($_SESSION)) session_destroy();
	session_id($vars[0]);
}
require_once(dirname(__FILE__)."/preload.php"); require_once dirname(__FILE__)."/header.php";?>
  <?php if ($config->getNode('secure','enabled') && $_SERVER['HTTPS'] == "off") {
	echo "Redirecting...";
	die("<script type='text/javascript'>window.location = '".$config->getNode('paths','secureRoot')."/checkout.php?".session_id()."';</script>");
}
?>
<h1 class="content">Checkout</h1>
<p>Total: &pound;<?php echo $basket->getFriendlyTotal(); ?></p>
ERR_FEATURE_NOT_IMPLEMENTED
<div class='ui-widget'>
  <div class='ui-widget-header'>Select Payment Method</div><div class='ui-widget-content'>
  <?php
  if (!$config->getNode('paypal','enabled')) {
	  ?>Sorry! This site is currently still in development, and we're still working on adding payment methods for your area. To order your items, please call us on [NUM] and quote your temporary ID number <?php echo $basket->getID();?>.<?php
  } else {
	  ?>
      <a href="checkout_paypal.php"><img src="images/paypal.png" /></a>
      <?php
  }
echo "</div></div>";
//Check for sufficient stock on items
$stock = $basket->checkAvail();
debug_message("Data received from StockChecker: ".print_r($stock,true));
if ($stock !== true) {
	//Insufficient Stock
	$item = new Item($stock);
	echo "<div class='ui-state-error ui-corner-all'><span class='ui-icon ui-icon-alert'></span>".$item->getName()."-> ".$config->getNode('messages','insufficientStock')." (Item #$stock)</div>";
}
require_once dirname(__FILE__)."/footer.php";
?>