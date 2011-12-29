<?php
require_once ("./preload.php");
require_once ("./paypalfunctions.php");
$paymentAmount = $_GET['CheckoutPrice'];
$currencyCodeType = "GBP";
$paymentType = "Sale";
$returnURL = "http://wallis2012.gotdns.com/shop/paypalFinish.php";
$cancelURL = "http://wallis2012.gotdns.com/shop/paypalCancel.php";

$resArray = CallShortcutExpressCheckout ($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL);
$ack = strtoupper($resArray["ACK"]);
if($ack=="SUCCESS")
{
	RedirectToPayPal ( $resArray["TOKEN"] );
} 
else  
{
	//Display a user friendly Error on the page using any of the following error information returned by PayPal
	$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
	$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
	$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
	$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
	
	trigger_error("SetExpressCheckout API Error -> $ErrorLongMsg. Code $ErrorCode, Severity $ErrorSeverityCode");
}
?>