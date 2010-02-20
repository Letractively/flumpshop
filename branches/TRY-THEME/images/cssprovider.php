<?php
//die();
require_once "../includes/vars.inc.php";
header("Content-Type: image/png");

echo file_get_contents($config->getNode('paths','offlineDir')."/themes/".$_GET['theme']."/images/".$_GET['image'].".png");
?>