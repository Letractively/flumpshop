<?php
//die();
require_once "../includes/vars.inc.php";

if (file_exists($config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/images/".$_GET['file'].".gif")) {
	header("Content-Type: image/gif");
	echo file_get_contents($config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/images/".$_GET['file'].".gif");
} elseif (file_exists($config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/images/".$_GET['file'].".png")) {
	header("Content-Type: image/png");
	echo file_get_contents($config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/images/".$_GET['file'].".png");
}
?>