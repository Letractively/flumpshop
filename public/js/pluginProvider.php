<?php
//die();
require_once "../includes/vars.inc.php";

if (!file_exists($config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/includes/".$_GET['file'].".js")) {
	header("HTTP/1.1 404 File Not Found");
	echo "alert('Error: Could not locate required ".$config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/includes/".$_GET['file'].".js');";
	exit;
}

header("Content-Type: text/javascript");
echo file_get_contents($config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/includes/".$_GET['file'].".js");
?>