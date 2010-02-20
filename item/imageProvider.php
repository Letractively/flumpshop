<?php
//die();
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
		} else {
			$file = $config->getNode('paths','offlineDir')."/images/item_placeholder.png";
		}
	}
} else {
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

header('Content-type: image/png');

echo file_get_contents($file);
?>