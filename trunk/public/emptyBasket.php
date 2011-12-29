<?php
require_once dirname(__FILE__)."/preload.php";
$basket->clear();
header("Location: ".$config->getNode('paths','root')."/basket.php");
?>