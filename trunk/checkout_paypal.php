<?php
//Transfer Session to HTTPS
$vars = array_keys($_GET);
if (isset($vars[0]) && is_string($vars[0])) {
	if (isset($_SESSION)) session_destroy();
	session_id($vars[0]);
}
define("PAGE_TYPE","plugins_forbidden");
require_once dirname(__FILE__)."/header.php";
if ($config->getNode('secure','enabled') && $_SERVER['HTTPS'] == "off") {
	echo "Redirecting...";
	die("<script type='text/javascript'>window.location = '".$config->getNode('paths','secureRoot')."/checkout.php?".session_id()."';</script>");
}
ob_start(); //Template Buffer
?><h1 class="content">Checkout</h1>
<p>Total: &pound;<?php echo $basket->getFriendlyTotal(true,true); ?></p>
<div class='ui-widget'>
  <div class='ui-widget-header'>Redirecting</div><div class='ui-widget-content'>
You are now being redirected to PayPal in order to pay for you order. You will be automatically returned to the site once the payment process is complete.
</div></div>
<?php
//CALLMARKEXPRESSCHECKOUT - ENTER ADDRESS LOCALLY
//Check for sufficient stock on items
$stock = $basket->checkAvail();
debug_message("Data received from StockChecker: ".print_r($stock,true));
if ($stock !== true) {
	//Insufficient Stock
	$item = new Item($stock);
	echo "<div class='ui-state-error ui-corner-all'><span class='ui-icon ui-icon-alert'></span>".$item->getName()."-> ".$config->getNode('messages','insufficientStock')." (Item #$stock)</div>";
} else {
	//Place Reserves on Items
	$expire = time()+($config->getNode('server','holdTimeout')*60);
	debug_message("Holds expire at ".date("d/m/y H/i/s",$expire));
	
	$basket->holdItem($expire); //Prevent Items from being purchased by anotehr user
	$basket->lock(); //Stop basket from being edited whilst at checkout
	//PayPal API call
	require_once dirname(__FILE__)."/paypalfunctions.php";
	CallShortcutExpressCheckout($basket->getFriendlyTotal(true,true),"GBP","Order",$config->getNode('paths','root')."/paypalFinish.php",$config->getNode('paths','root')."/paypalCancel.php");
	$token = @$_SESSION['TOKEN'];
	RedirectToPayPal($token);
}

templateContent();
require_once dirname(__FILE__)."/footer.php";
?>