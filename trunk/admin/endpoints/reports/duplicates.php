<?php
$USR_REQUIREMENT = "can_view_reports";
require_once dirname(__FILE__)."/../header.php";

echo "<h1>Suggested Content</h1>";
echo "<p>Below will be listed any content that has been detected as duplicate, unnecessary, or invalid. It is recommended that you take steps to rectify these issues to provide a better service.</p>";
//Detect duplicate images
$basedir = $config->getNode("paths","offlineDir")."/images/";

$image_sha1 = array();

$dp = opendir($basedir);
echo "<table class='ui-widget'>";
echo "<tr><td colspan=2><h2>Unnecessary Images</h2></td></tr>";
echo "<tr class='ui-widget-header'><th>Image</th><th>Reason</th></tr>";
while ($dir = readdir($dp)) {
	if (preg_match("/^item_([0-9])*$/i",$dir)) {
		$itemID = preg_replace("/^item_([0-9])*$/i","$1",$dir);
		//Check if item hasn't been deleted
		if ($dbConn->rows($dbConn->query("SELECT id FROM `products` WHERE id=$itemID AND active=1 LIMIT 1")) == 0) {
			echo "<tr class='ui-widget-content'><td>$dir/*</td><td>Item has been removed.</td></tr>";
		} else {
			//Is an item image resource dir, search for duplicates (Don't bother if mothballed)
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
						echo "<tr class='ui-widget-content'><td>".$dir."/".$file."</td><td>Duplicate of ".$result."</td></tr>";
					}
				}
			}
		}
	}
}
echo "</table>";

//Detect short descriptions (<150 characters)
$min = 150;
$result = $dbConn->query("SELECT id FROM `products` WHERE LENGTH(description) < $min");

echo "<h2>Short Descriptions</h2><table>";
echo "<tr class='ui-widget-header'><th>Item</th><th>Description</th></tr>";
while ($row = $dbConn->fetch($result)) {
	$item = new Item($row['id']);
	echo "<tr class='ui-widget-content'><td><a href='../edit/editItem.php?id=".$item->getID()."&amp;return=report'>".$item->getName()."</a></td><td>".$item->getDesc()."</td></tr>";
}
?>