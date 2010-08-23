<?php
//gzip compression
ob_start("ob_gzhandler");
require_once dirname(__FILE__)."/../includes/vars.inc.php";

if (!isset($_GET['random'])) {
	$item = strval($_GET['id']);
	if (isset($_GET['image'])) $image = $_GET['image']; else $image = 0;
	if (isset($_GET['size'])) $size = $_GET['size']; else $size = "full";
	
	$file = $config->getNode('paths','offlineDir')."/images/item_$item/".$size."_".$image.".png";
	
	if (!file_exists($file)) {
		if (strlen($item) < 10) {
			while (strlen($item) < 10) {
				$item = strval("0".$item);
			}
			$file = $config->getNode('paths','offlineDir')."/images/item_$item/".$size."_".$image.".png";
			if (!file_exists($file)) {
				$file = $config->getNode('paths','offlineDir')."/images/item_placeholder.png";
			}
		} elseif (preg_match("/^0/",$item)) {
			$item = preg_replace("/^0*/","",$item);
			$file = $config->getNode('paths','offlineDir')."/images/item_$item/".$size."_".$image.".png";
			if (!file_exists($file)) {
				$file = $config->getNode('paths','offlineDir')."/images/item_placeholder.png";
			}
		} else {
			$file = $config->getNode('paths','offlineDir')."/images/item_placeholder.png";
		}
	}
} else {
	//Choose random images (and use thumbs)
	$dirs = array();
	$dir = opendir($config->getNode('paths','offlineDir')."/images");
	while ($subdir = readdir($dir)) {
		if (strstr($subdir,"item_") && is_dir($config->getNode('paths','offlineDir')."/images/".$subdir)) {
			$dirs[] = $subdir;
		}
	}
	shuffle($dirs);
	$dir = $config->getNode('paths','offlineDir')."/images/".array_pop($dirs);
	$file = $dir."/thumb_0.png";
}

header("Content-Type: image/png");
header("Cache-control: max-age=604800, public"); //1 Week
header("Expires: ".date("D, d M Y H:i:s T",604800));
header('Pragma: ');
echo file_get_contents($file);
?>