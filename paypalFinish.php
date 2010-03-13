<?php require_once("./preload.php"); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="../Templates/Default.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $config->getNode('messages','name');?> | Payment Complete</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<link href="<?php echo $config->getNode('paths','root');?>/style/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jqueryui.js"></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jeditable.js"></script>
<link href="<?php echo $config->getNode('paths','root');?>/style/jquery.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $config->getNode('paths','root');?>/style/jquery-overrides.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtr">

<div id="container">
  <div id="mainContent" class="ui-widget">
  <h1><a href="<?php echo $config->getNode('paths','root');?>/index.php"><?php echo $config->getNode('messages', 'name');?></a></h1>
  <div class="ui-widget-content" style="float: right; width: auto; padding-right: 1em;"><a href='<?php echo $config->getNode('paths','root');?>/basket.php'><span class="ui-icon ui-icon-cart"></span><?php echo $basket->getItems(); ?> Item(s) | &pound;<?php echo $basket->getFriendlyTotal(); ?></a></div>
  <?php if ($_PRINTDATA) echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-alert'></span>Debug Mode Enabled.</div>";?>
  <!-- InstanceBeginEditable name="docbody" -->
    <?php require_once("./paypalfunctions.php");
	$result = GetShippingDetails($_GET['token']);
	if ($dbConn->rows($dbConn->query("SELECT id FROM `orders` WHERE token='".$_GET['token']."' LIMIT 1"))) {
		echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-alert'></span>This order has already been placed.</div>";
	} else {
		if (strtoupper($result['ACK']) == "SUCCESS") {
			//Standardize Strings
			$keys = array_keys($result);
			foreach ($keys as $key) {
				$result[$key] = ucwords(strtolower($result[$key]));
			}
			$result["EMAIL"] = strtolower($result["EMAIL"]);
			$result["SHIPTOZIP"] = strtoupper($result["SHIPTOZIP"]);
			//Prevent Stock From Being Re-sold
			$basket->commitHolds();
			//Query to locate existing customer
			$customers = $dbConn->query("SELECT id FROM `customers` WHERE name='".str_replace("'","''",$result['SHIPTONAME'])."' 
										AND address1='".str_replace("'","''",$result['SHIPTOSTREET'])."' 
										AND address2='".str_replace("'","''",$result['SHIPTOCITY'])."' 
										AND address3='".str_replace("'","''",$result['SHIPTOSTATE'])."' 
										AND postcode='".str_replace("'","''",$result['SHIPTOZIP'])."' 
										AND country='".str_replace("'","''",$result['SHIPTOCOUNTRYNAME'])."' 
										AND email='".str_replace("'","''",$result['EMAIL'])."' LIMIT 1");
			if ($dbConn->rows($customers) == 0) {
				debug_message("Generating new Customer Object...");
				//Generate New Customer Record
				$customer = new Customer();
				$customer->populate($result['SHIPTONAME'],$result['SHIPTOSTREET'],$result['SHIPTOCITY'],$result['SHIPTOSTATE'],$result['SHIPTOZIP'],$result['SHIPTOCOUNTRYNAME'],$result['EMAIL'],$_GET['PayerID']);
			} else {
				debug_message("Loading Existing Customer Object...");
				$customer = $dbConn->fetch($customers);
				$customer = new Customer($customer['id']);
			}
			$order = $basket->commitOrder($customer,$_GET['token']);
			if (!$order) echo "Fatal Error: Could not commit order. Contact Support with SESSIONID ".session_id();
			debug_message(print_r($result,true));
			
			//Clear Basket (also stops duplicate ordering)
			debug_message("Initializing new basket");
			$dbConn->query("INSERT INTO `basket` (obj) VALUES (' ')");
			$bid = $dbConn->insert_id();
			$dbConn->query("UPDATE `sessions` SET basket=$bid WHERE session_id='".session_id()."' LIMIT 1");
			$basket = new Cart($bid);
			?>
			<h1>Payment Confirmed</h1>
			<div class="ui-widget ui-widget-title ui-corner-top">Your Order</div>
			<div class="ui-widget-content ui-corner-bottom">Your order has been accepted and has been assigned order id number <b><?php echo $order; ?></b>. Please print this page for your records, and make a note of this order number, and use it if you need to contact us regarding this order.</div>
			<h2>Shipping To</h2>
			<div class="ui-widget-content ui-corner-all" style="width: 48%; float: left;">
			<?php
			echo $result['SHIPTONAME']."<br />";
			echo $result['SHIPTOSTREET']."<br />";
			echo $result['SHIPTOCITY']."<br />";
			echo $result['SHIPTOSTATE']."<br />";
			echo $result['SHIPTOZIP']."<br />";
			echo $result['SHIPTOCOUNTRYNAME']."<br />";
			?>
			</div>
			<div class="ui-widget-content ui-corner-all" style="width: 48%; float: right;">
			<h4>What Next?</h4>
			<p><?php echo $config->getNode("messages", "paymentComplete");?></p>
			</div>
			<?php
		} else {
			trigger_error("Result: ".$result['ACK']);
			?>
			<h1>Payment Failed</h1>
			<p><?php echo $config->getNode("messages", "transactionFailed");?></p>	
			<?php
		}
	}
	?>
  <!-- InstanceEndEditable -->
	<!-- end #mainContent --></div>
<!-- end #container --></div>
<?php echo $config->getNode('messages','footer');?>
<!-- InstanceBeginEditable name="scripts" -->
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
