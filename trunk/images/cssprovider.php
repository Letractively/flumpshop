<?php
//die();
require_once "../includes/vars.inc.php";

if (file_exists($config->getNode('paths','offlineDir')."/themes/".$_GET['theme']."/images/".$_GET['image'].".gif")) {
	header("Content-Type: image/gif");
	echo file_get_contents($config->getNode('paths','offlineDir')."/themes/".$_GET['theme']."/images/".$_GET['image'].".gif");
} elseif (file_exists($config->getNode('paths','offlineDir')."/themes/".$_GET['theme']."/images/".$_GET['image'].".png")) {
	header("Content-Type: image/png");
	echo file_get_contents($config->getNode('paths','offlineDir')."/themes/".$_GET['theme']."/images/".$_GET['image'].".png");
}
?>