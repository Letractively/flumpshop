<?php
require_once "preload.php";
$item = new Item(intval($_GET['id']));

$newQuantity = intval($_POST['itemQuantity']);

if ($item->getStock() < $newQuantity) {
	//Not enough stock
	header("Location: basket.php?insufficientStock");
} elseif ($newQuantity < 1) {
	//Invalid Number
	header("Location: basket.php?invalidParameter");
} else {
	$basket->changeQuantity($item->getID(),$newQuantity);
	header("Location: basket.php?stockUpdated");
}
?>