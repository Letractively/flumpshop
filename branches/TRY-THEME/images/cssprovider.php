<?php
require_once "../preload.php";
header("Content-Type: image/png");

echo file_get_contents($config->getNode('paths','offlineDir')."/themes/".$_GET['theme']."/images/".$_GET['image'].".png");
?>