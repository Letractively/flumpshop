<?php
require_once "../preload.php";
header("Content-Type: text/css");

echo file_get_contents($config->getNode('paths','offlineDir')."/themes/".$_GET['theme']."/".$_GET['sub'].".css");
?>