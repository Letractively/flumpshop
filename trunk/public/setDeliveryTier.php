<?php
//Updates the Basket delivery tier
require_once './preload.php';

$basket->setDeliveryTier(intval($_GET['tid']));
$basket->checkPrice();
header('Location: ./basket.php');
?>