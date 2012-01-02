<?php
$ajaxProvider = true;
require_once dirname(__FILE__) . "/../../includes/preload.php";
header("Content-Type: text/css");

echo file_get_contents($initcfg->getNode('paths','offlineDir')."/themes/".$_GET['theme']."/".$_GET['sub'].".css");
