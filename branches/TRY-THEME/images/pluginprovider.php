<?php
require_once "../preload.php";
header("Content-Type: image/png");

echo file_get_contents($config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/images/".$_GET['file'].".png");
?>