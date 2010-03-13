<?php
//Extend timeout period
set_time_limit(0);
//Updates images to correct aspect ratio (Issue 30, r188)
require_once dirname(__FILE__)."/../header.php";
$time_start = microtime_float();
file_put_contents($config->getNode('paths','offlineDir')."/admin_image_rebuild_status.txt","Scanning for images...");
echo "Scanning for images...<br />";
$dp = opendir($config->getNode("paths","offlineDir")."/images");
if (!$i = file_get_contents($config->getNode("paths","offlineDir")."/admin_image_rebuild_skip.txt")) {$i = 0;}
//Skip processed ones on resume
if ($i != 0) {
	$n = $i;
	while ($n > 0) {
		readdir($dp);
	}
}
while ($dir = readdir($dp)) {
	if (preg_match("/^item_([0-9])*$/i",$dir)) {
		//Is an item image resource dir
		$dp2 = opendir($config->getNode("paths","offlineDir")."/images/".$dir);
		$itemID = preg_replace("/^item_([0-9]*)$/i","$1",$dir);
		file_put_contents($config->getNode('paths','offlineDir')."/admin_image_rebuild_status.txt","Rebuilding Item $itemID...");
		file_put_contents($config->getNode("paths","offlineDir")."/admin_image_rebuild_skip.txt",$i);
		echo "Rebuilding Item $itemID...<br />";
		$item = new Item($itemID);
		while ($file = readdir($dp2)) {
			if (preg_match("/^full_([0-9])*\.png$/",$file)) {
				//Update progress status
				file_put_contents($config->getNode('paths','offlineDir')."/admin_image_rebuild_status.txt","Rebuilding Item $itemID image $file...");
				echo "Rebuilding Item $itemID image $file...<br />";
				
				$imageNo = preg_replace("/^full_([0-9]*)\.png$/","$1",$file);
				//Is a full item image
				//Create Backup Copy
				copy($config->getNode("paths","offlineDir")."/images/".$dir."/".$file,$config->getNode("paths","offlineDir")."/images/".$dir."/".$file.".bak");
				//Delete Originals
				unlink($config->getNode("paths","offlineDir")."/images/".$dir."/full_".$imageNo.".png");
				unlink($config->getNode("paths","offlineDir")."/images/".$dir."/thumb_".$imageNo.".png");
				unlink($config->getNode("paths","offlineDir")."/images/".$dir."/minithumb_".$imageNo.".png");
				
				//Rebuild From Backup
				$item->saveImage($config->getNode("paths","offlineDir")."/images/".$dir."/".$file.".bak","image/png");
			}
		}
	}
}
file_put_contents($config->getNode('paths','offlineDir')."/admin_image_rebuild_status.txt", "Images rebuilt in ".(microtime_float()-$time_start)." seconds.");
unlink($config->getNode("paths","offlineDir")."/admin_image_rebuild_skip.txt");
echo "Images rebuilt in ".(microtime_float()-$time_start)." seconds."
?></body></html>