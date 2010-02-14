<?php
//Updates images to correct aspect ratio (Issue 30, r188)
require_once dirname(__FILE__)."/../header.php";
$dp = opendir($config->getNode("paths","offlineDir")."/images");
while ($dir = readdir($dp)) {
	if (preg_match("/^item_([0-9])*$/i",$dir)) {
		//Is an item image resource dir
		$dp2 = opendir($config->getNode("paths","offlineDir")."/images/".$dir);
		$itemID = preg_replace("/^item_([0-9]*)$/i","$1",$dir);
		$item = new Item($itemID);
		while ($file = readdir($dp2)) {
			if (preg_match("/^full_([0-9])*\.png$/",$file)) {
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
?><div class="ui-state-highlight"><span class="ui-icon ui-icon-info"></span>Images have been rebuilt. Originals can be found with the .bak extension.</div>