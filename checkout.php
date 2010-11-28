<?php
$NOCACHE = true;
define("PAGE_TYPE","checkout");
//Transfer Session to HTTPS
$vars = array_keys($_GET);
if (isset($vars[0]) && is_string($vars[0])) {
	if (isset($_SESSION)) session_destroy();
	session_id($vars[0]);
}
require_once(dirname(__FILE__)."/preload.php"); require_once dirname(__FILE__)."/header.php";

ob_start(); //Template Buffer

//Recalculate
$basket->checkAvail();
$basket->checkPrice();

if ($config->getNode('secure','enabled') && $_SERVER['HTTPS'] == "off") {
	echo "Redirecting...";
	echo("<script type='text/javascript'>window.location = '".$config->getNode('paths','secureRoot')."/checkout.php?".session_id()."';</script>");
} else {
	?><h1 class="content"><?php echo $config->getNode("messages","checkoutHeader");?></h1>
	<p>Total: &pound;<?php echo $basket->getFriendlyTotal(true,true); ?></p>
	<div class='ui-widget'>
	  <div class='ui-widget-header'><?php echo $config->getNode("messages","paymentMethodHeader");?></div><div class='ui-widget-content'>
	<script type="text/javascript">
	function loadingDialog() {
		$("#dialog").html("<img src='images/loading.gif' />Please wait whilst we finalise the order details.");
		document.getElementById("dialog").title = "Please Wait";
		$("#dialog").dialog({buttons: {},closeOnEscape:false,modal:true});
	}
	</script>
	  <?php
	  if (!$config->getNode('paypal','enabled')) {
		  ?>Sorry, we're still tweaking the online order system. In the meantime, we've created an ID number for your order. To order your items, please call us on 01487 830968 and quote your temporary ID number <?php echo $basket->getID();?>.<?php
		  
	  } else {
		  ?><a href="checkout_paypal.php" onclick="loadingDialog();"><img src="images/paypal.gif" alt="Checkout with PayPal" /></a><?php
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
}
templateContent();
require_once dirname(__FILE__)."/footer.php";
?>