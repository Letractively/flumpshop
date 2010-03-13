<?php
require_once dirname(__FILE__)."/../header.php";

echo "<h1>Duplicate Content</h1>";
echo "<p>Below will be listed any content that has been detected as duplicate. If possible, these should be removed, changed or merged to reduce confusion.</p>";
//Detect duplicate images
echo "<h2>Duplicate Images</h2>";
$basedir = $config->getNode("paths","offlineDir")."/images/";

$image_sha1 = array();

$dp = opendir($basedir);

while ($dir = readdir($dp)) {
	if (preg_match("/^item_([0-9])*$/i",$dir)) {
		//Is an item image resource dir
		$dp2 = opendir($basedir.$dir);
		while ($file = readdir($dp2)) {
			if (preg_match("/^full_[0-9]*\.png$/",$file)) {
				$hash = sha1_file($basedir.$dir."/".$file);
				$result = array_search($hash,$image_sha1);
				if (!$result) {
					//Unique file
					$image_sha1[$dir."/".$file] = $hash;
				} else {
					//Duplicate file
					echo $dir."/".$file." is a duplicate of ".$result.".<br /><br />";
				}
			}
		}
	}
}
?>