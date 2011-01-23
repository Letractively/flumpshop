<?php
require_once "../../../../preload.php";
loadClass('Keycode');

acpusr_validate() or die($config->getNode('messages','adminDenied'));

header("Cache-control: max-age=3600, must-revalidate, public");
header("Expires: ".date("D, d M Y H:i:s T",time()+(3600*24)));

$coupon = new Keycode(-1,str_replace("'","''",$_GET['id']));

if ($coupon->id == -1) {
	echo json_encode(array("This Coupon is invalid.",""));
} else {
	echo json_encode(array($coupon->getFriendlyDesc(),$coupon->actions[0]));
}
?>