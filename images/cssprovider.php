<?php
//die();
require_once "../includes/vars.inc.php";

if (file_exists($config->getNode('paths','offlineDir')."/themes/".$_GET['theme']."/images/".$_GET['image'].".gif")) {
	header("Content-Type: image/gif");
	header("Cache-control: max-age=".(3600*24).", must-revalidate, public");
	header("Expires: ".date("D, d M Y H:i:s T",time()+(3600*24)));
	echo file_get_contents($config->getNode('paths','offlineDir')."/themes/".$_GET['theme']."/images/".$_GET['image'].".gif");
} elseif (file_exists($config->getNode('paths','offlineDir')."/themes/".$_GET['theme']."/images/".$_GET['image'].".png")) {
	header("Content-Type: image/png");
	header("Cache-control: max-age=".(3600*24).", must-revalidate, public");
	header("Expires: ".date("D, d M Y H:i:s T",time()+(3600*24)));
	echo file_get_contents($config->getNode('paths','offlineDir')."/themes/".$_GET['theme']."/images/".$_GET['image'].".png");
}
?>