<?php
require_once dirname(__FILE__)."/preload.php";
$id = $_GET['id'];
$basket->removeItem($id);
header("Location: ".$config->getNode('paths','root')."/basket.php");
?>