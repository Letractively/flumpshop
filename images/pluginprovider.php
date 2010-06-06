<?php
//die();
require_once "../includes/vars.inc.php";

if (file_exists($config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/images/".$_GET['file'].".gif")) {
	header("Content-Type: image/gif");
	header("Cache-control: max-age=".(3600*24).", must-revalidate, public");
	header("Expires: ".date("D, d M Y H:i:s T",time()+(3600*24)));
	echo file_get_contents($config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/images/".$_GET['file'].".gif");
} elseif (file_exists($config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/images/".$_GET['file'].".png")) {
	header("Content-Type: image/png");
	header("Cache-control: max-age=".(3600*24).", must-revalidate, public");
	header("Expires: ".date("D, d M Y H:i:s T",time()+(3600*24)));
	echo file_get_contents($config->getNode('paths','offlineDir')."/plugins/".$_GET['mod']."/images/".$_GET['file'].".png");
}
?>